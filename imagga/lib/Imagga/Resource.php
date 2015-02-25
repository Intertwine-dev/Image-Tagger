<?php

namespace Imagga\Imagga;


class Resource {

    protected $_uri;
    protected $_http;
    protected $_resultType;

    public function __construct()
    {
        $this->_http = new Http(Configuration::getInstance());
    }

}