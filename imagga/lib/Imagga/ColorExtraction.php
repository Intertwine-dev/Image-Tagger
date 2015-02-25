<?php

namespace Imagga\Imagga;


class ColorExtraction extends Processor {

    protected $_uri = '/colors';
    protected $_resultType = 'ColorExtractionResult';

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