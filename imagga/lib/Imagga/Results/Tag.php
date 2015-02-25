<?php

namespace Imagga\Imagga\Results;


class Tag {

    private $_name;
    private $_confidence;

    public function getLabel()
    {
        return $this->_name;
    }

    public function getConfidence()
    {
        return $this->_confidence;
    }

    public function __construct($label, $confidence)
    {
        if ( !is_string($label) )
        {
            throw new \InvalidArgumentException('Invalid label type - expected string.');
        }

        if ( !is_numeric($confidence) )
        {
            throw new \InvalidArgumentException('Invalid confidence type - expected number.');
        }

        $this->_name = $label;
        $this->_confidence = floatval($confidence);
    }

    /**
     * @param $tagA Tag
     * @param $tagB Tag
     * @return int
     */
    public static function compare($tagA, $tagB)
    {
        $aConf = $tagA->getConfidence();
        $bConf = $tagB->getConfidence();
        if ($aConf == $bConf)
        {
            return 0;
        }
        return ($aConf > $bConf) ? -1 : +1;
    }
}