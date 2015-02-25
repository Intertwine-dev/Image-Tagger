<?php

namespace Imagga\Imagga;


class Content extends Resource {

    protected $_uri = '/content';
    protected $_resultType = 'Content';

    public function upload($filePath, $params=array())
    {
        $httpResp = $this->_http->upload($this->_uri, $filePath, $params);
        return new Response($httpResp['data'], $httpResp['status'], $this->_resultType);
    }

}