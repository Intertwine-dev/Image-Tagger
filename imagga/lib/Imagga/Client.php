<?php

namespace Imagga\Imagga;
use Exception;


/**
 * Client is the parent class of all Imagga API services. In most of the cases this is the only class
 * you will need in order to use Imagga API.
 * You should first obtain your api key and secret by
 * signing up on https://imagga.com/auth/signup
 */
class Client {

	protected $conf;

	private $availableMethods = array();

	public function __construct($apiKey, $apiSecret)
	{
		$this->conf = Configuration::getInstance();
		$this->conf->setEndpoint('api.imagga.com');
		$this->conf->setSSLOff();
		$this->conf->setApiKey($apiKey);
		$this->conf->setApiSecret($apiSecret);
		
		$this->availableMethods = array(
			'tagging' => '\Imagga\Imagga\Tagging',
			'colorExtraction' => '\Imagga\Imagga\ColorExtraction',
			'cropping' => '\Imagga\Imagga\Cropping',
			'categorization' => '\Imagga\Imagga\Categorization',
            'uploadContent' => array(
                'class' => '\Imagga\Imagga\Content',
                'main' => 'upload'
            )
		);
	}

    /**
     * @param $protocol
     */
    public function changeProtocol($protocol)
	{
		$this->conf->setProtocol($protocol);
	}

	public function getApiKey()
	{
		return $this->conf->apiKey;
	}

	public function setApiKey($apiKey)
	{
		$this->conf->setApiKey($apiKey);
	}

	public function getApiSecret()
	{
		return $this->conf->getApiSecret();
	}

	public function setApiSecret($apiSecret)
	{
		$this->conf->setApiSecret($apiSecret);
	}

	public function getVersion()
	{
		return Configuration::API_VERSION;
	}

	public function getEndpoint()
    {
        return $this->conf->getEndpoint();
    }

	public function __call($method, $args)
	{
		if ( in_array($method, array_keys($this->availableMethods)) )
        {
            if ( count($args) < 2 ) {
                $args[1] = array();
            }
            if ( is_array($this->availableMethods[$method]) ) {
                $mainMethod = $this->availableMethods[$method]['main'];
                $className = $this->availableMethods[$method]['class'];
                $solution = new $className($this->conf->getApiKey(), $this->conf->getApiSecret());
                return $solution->{$mainMethod}($args[0]);
            } else {
                $solution = new $this->availableMethods[$method]($this->conf->getApiKey(), $this->conf->getApiSecret());

                if (count($args) == 0) {
                    return $solution;
                } elseif ($args[0] instanceof Results\Content) {
                    return $solution->processContent($args[0], $args[1]);
                } elseif (is_array($args[0])) {
                    return $solution->processUrls($args[0], $args[1]);
                } elseif (is_string($args[0])) {
                    return $solution->processUrls(array($args[0]), $args[1]);
                } else {
                    throw new Exception('The type of the image submitted for processing is not supported.');
                }
            }
        }
		trigger_error('Unknown method '.__CLASS__.':'.$method, E_USER_ERROR);
	}
}
