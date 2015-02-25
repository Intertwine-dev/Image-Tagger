<?php

namespace Imagga\Imagga;


class Response {

    private $_results;
    private $_errors;

    public function __construct($respDataRaw, $statusCode, $resultsType)
    {
        $respData = json_decode($respDataRaw);
        if ( !class_exists(__NAMESPACE__ . '\Results\\'.$resultsType) )
        {
            throw new \Exception('Class ' . $resultsType . ' does not exist.');
        }
        if ( in_array($statusCode, array(200, 201, 202)) &&
            !(isset($respData->status) && $respData->status == 'error') )
        {
            $this->_results = call_user_func(array(__NAMESPACE__ . '\Results\\' . $resultsType, 'fromJson'),
                $respDataRaw);
            $this->_errors = null;
        }
        elseif ($respData->status == 'error')
        {
            $this->_errors = array();
            if ( isset( $respData->message ) )
            {
                $this->_errors[] = new Results\Error($respData->message, $statusCode);
            }
            else
            {
                $this->_errors[] = new Results\Error('Unexpected error.', $statusCode);
            }
        }
        else
        {
            $this->_errors = array();
            $this->_errors[] = new Results\Error('Unexpected error.', $statusCode);
        }
    }

    /**
     * @return null|array
     */
    public function getErrors()
    {
        return $this->_errors;
    }

    /**
     * @return array
     */
    public function getResults()
    {
        return $this->_results;
    }

}