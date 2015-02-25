<?php

namespace Imagga\Imagga\Results;


class Cropping {

    private $_x1;
    private $_x2;
    private $_y1;
    private $_y2;
    private $_width;
    private $_height;

    public function __construct($cropping)
    {
        $requiredKeys = array('x1', 'x2', 'y1', 'y2', 'target_width', 'target_height');
        if ( count(array_intersect(array_keys((array)$cropping), $requiredKeys)) != count($requiredKeys) )
        {
            throw new \InvalidArgumentException('Incomplete cropping data.');
        }

        $this->_x1 = $cropping->x1;
        $this->_x2 = $cropping->x2;
        $this->_y1 = $cropping->y1;
        $this->_y2 = $cropping->y2;
        $this->_width = $cropping->target_width;
        $this->_height = $cropping->target_height;
    }

    public function getCoords()
    {
        return array(
            'x1' => $this->_x1,
            'y1' => $this->_y1,
            'x2' => $this->_x2,
            'y2' => $this->_y2
        );
    }

    public function getResolution()
    {
        return array(
            'width' => $this->_width,
            'height' => $this->_height
        );
    }

}