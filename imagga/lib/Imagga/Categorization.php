<?php

namespace Imagga\Imagga;


class Categorization extends Processor {

    protected $_uri = '/categorizations';
    protected $_resultType = 'CategorizationResult';
    private $_uriParams = array('categorizer');
    private $_defaultCategorizer = 'personal_photos';

    public function processUrls($urls, $params=array())
    {
        if ( !in_array('categorizer', $params) )
        {
            $params['categorizer'] = $this->_defaultCategorizer;
        }

        foreach ($this->_uriParams as $key => $param)
        {
            if ( !array_key_exists($param, $params) ) {
                // TODO: Throw an exception
                return false;
            }
            $this->_uriParams[$key] = $params[$param];
        }
        $this->_uri = $this->_uri . '/' . implode('/', $this->_uriParams);
        $httpResp = $this->_sendUrls($urls, $params);
        return new Response($httpResp['data'], $httpResp['status'], $this->_resultType);
    }

    public function processContent($content, $params=array())
    {
        $httpResp = $this->_sendContent($content, $params);
        return new Response($httpResp['data'], $httpResp['status'], $this->_resultType);
    }

}