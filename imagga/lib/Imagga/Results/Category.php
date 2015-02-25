<?php

namespace Imagga\Imagga\Results;


class Category {

    private $_name;
    private $_confidence;

    public function __construct($name, $confidence)
    {
        $this->_name = $name;
        $this->_confidence = floatval($confidence);
    }

    /**
     * @param mixed $confidence
     */
    public function setConfidence($confidence)
    {
        $this->_confidence = floatval($confidence);
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return mixed
     */
    public function getConfidence()
    {
        return $this->_confidence;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->_name;
    }


}