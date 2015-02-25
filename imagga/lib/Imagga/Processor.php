<?php

namespace Imagga\Imagga;


use Exception;

abstract class Processor extends Resource {

    abstract public function processUrls($urls, $params=array());
    abstract public function processContent($content, $params=array());

    protected function _getContentIds($content) {
        $contentIds = array();
        if ( $content instanceof Results\Content )
        {
            $contentIds[] = $content->getId();
        }
        elseif ( is_array($content) )
        {
            foreach ($content as $one)
            {
                if ( !$content instanceof Results\Content )
                {
                    throw new Exception('Expected array of Content objects.');
                }
                $contentIds[] = $one->getId();
            }
        }
        else
        {
            throw new Exception('Expected object of type Content.');
        }
        return $contentIds;
    }

    protected function _sendUrls($urls, $params)
    {
        if ( !is_array($urls) )
        {
            return false;
        }
        return $this->_http->get($this->_uri, array('url' => $urls));
    }

    protected function _sendContent($content, $params)
    {
        $contentIds = $this->_getContentIds($content);
        return $this->_http->get($this->_uri, array(
            'content' => $contentIds
        ));
    }
}