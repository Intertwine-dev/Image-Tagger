<?php

namespace Imagga\Imagga;


class Configuration {
	
	public static $global = null;
	/**
	 * Client API key
	 * @access private
	 */
	private $_apiKey;

	/**
	 * Client API secret
	 */
	private $_apiSecret;

	/**
	 * Imagga API endpoint
	 */
	private $_apiEndpoint;

	private $_apiProtocol;

	private $_ssl;

	private $_authorization;

	/**
	 * Imagga API version
	 * @access private
	 */
	const API_VERSION = 'v1';

	protected function __construct()
	{
		$this->_ssl = true;
		$this->_authorization = 'Basic';
	}

	public function setApiKey($apiKey)
	{
		$this->_apiKey = $apiKey;
	}

	public function setApiSecret($apiSecret)
	{
		$this->_apiSecret = $apiSecret;
	}

	public function getApiKey()
	{
		return $this->_apiKey;
	}

	public function getApiSecret()
	{
		return $this->_apiSecret;
	}

	public function getEndpoint()
	{
		return (($this->_ssl) ? 'https://' : 'http://') . $this->_apiEndpoint . '/' . Configuration::API_VERSION;
	}

	public function setEndpoint($endpoint)
	{
		$this->_apiEndpoint = $endpoint;
	}

	public function setSSLOn()
	{
		$this->_ssl = true;
	}

	public function setSSLOff()
	{
		$this->_ssl = false;
	}

	public function secureRequest()
	{
		return $this->_ssl;
	}

	public function getCAFile()
	{
		//
	}

	public static function getInstance()
	{
		if ( null === self::$global )
		{
			self::$global = new Configuration();
		}
		return self::$global;
	}
}