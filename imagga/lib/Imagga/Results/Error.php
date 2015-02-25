<?php

namespace Imagga\Imagga\Results;


class Error {
    private $_message;
    private $_statusCode;

    public function __construct($message, $statusCode)
    {
        $this->_message = $message;
        $this->_statusCode = $statusCode;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->_statusCode;
    }
}