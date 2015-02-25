<?php

namespace Imagga\Imagga;


class Cropping extends Processor {

    protected $_uri = '/croppings';
    protected $_resultType = 'CroppingResult';

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