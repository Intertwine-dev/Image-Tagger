<?php

namespace Imagga\Imagga;


class Tagging extends Processor {

	protected $_uri = '/tagging';
    protected $_resultType = 'TaggingResult';

	public function processUrls($urls, $params=array())
	{
        $httpResp = $this->_sendUrls($urls, $params);
        return new Response($httpResp['data'], $httpResp['status'], $this->_resultType);
	}

	public function processContent($content, $params=array())
	{
        $httpResp = $this->_sendContent($content, $params);
        return new Response($httpResp['data'], $httpResp['status'], $this->_resultType);
	}

}