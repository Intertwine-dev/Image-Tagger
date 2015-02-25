<?php

namespace Imagga\Imagga;


class Http {

	protected $_config;

	public function __construct($config)
	{
		$this->_config = $config;
	}

	public function get($uri, $params)
	{
		return $this->_APIRequest('GET', $uri, $params);
	}

	public function post($uri, $params)
	{
		return $this->_APIRequest('POST', $uri, $params);
	}

    public function upload($uri, $filePath, $params)
    {
        $paramValue = "@{$filePath};filename=".basename($filePath);
        $params['image'] = $paramValue;
        return $this->post($uri, $params);
    }

	public function put($uri, $params)
	{
		return $this->_APIRequest('PUT', $uri, $params);
	}

	public function delete($uri, $params)
	{
		return $this->_APIRequest('DELETE', $uri, $params);
	}

	private function _APIRequest($method, $uri, $params = null)
	{
		return $this->_doRequest($method, $this->_config->getEndpoint() . $uri, $params);
	}

	private function _doRequest($method, $url, $params = null)
	{
		$curl = curl_init();

		$query_string = array();
		if ( $params && $method == 'GET' )
		{
			foreach ($params as $param => $value)
			{
                if ( is_array($value) )
                {
                    foreach ($value as $v)
                    {
                        $query_string[] = $param.'='.$v;
                    }
                }
                else
                {
                    $query_string[] = $param.'='.$value;
                }
			}
		}

		$url = $url . '?' . implode('&', $query_string);

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($curl, CURLOPT_TIMEOUT, 60);
		curl_setopt($curl, CURLOPT_USERAGENT, 'Imagga PHP SDK');
		curl_setopt($curl, CURLOPT_USERPWD, $this->_config->getApiKey() . ':' . $this->_config->getApiSecret());

		if ( $method == 'POST' )
		{
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
		}

		curl_setopt($curl, CURLOPT_HEADER, array(
			'Accept: application/json',
			'Content-Type: application/json'
		));

		if ( $this->_config->secureRequest() )
		{
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($curl, CURLOPT_CAINFO, $this->_config->getCaFile());
		}

		$time_start = time();
		$response = curl_exec($curl);
		$time_end = time();
		$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
		
		$header = substr($response, 0, $headerSize);
		$body = substr($response, $headerSize);

		curl_close($curl);

		if ($this->_config->secureRequest())
		{
			if ($httpCode == 0)
			{
				throw new Exception('Unable to verify server identity.');
			}
		}
		return array( 'status' => $httpCode, 'data' => $body, 'time_processing' => ($time_end - $time_start) );
	}

}