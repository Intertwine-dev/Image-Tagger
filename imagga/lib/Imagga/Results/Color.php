<?php

namespace Imagga\Imagga\Results;


class Color {

    private $_red;
    private $_green;
    private $_blue;

    private $_htmlCode;

    private $_percentage;

    private $_closestPalette;

    /**
     * @param object $colorData
     */
    public function __construct($colorData)
    {
        $this->_red = $colorData->r;
        $this->_green = $colorData->g;
        $this->_blue = $colorData->b;

        $this->_htmlCode = $colorData->html_code;
        $this->_percentage = $colorData->percent;

        $this->_closestPalette = array(
            'name' => $colorData->closest_palette_color,
            'html_code' => $colorData->closest_palette_color_html_code,
            'parent' => $colorData->closest_palette_color_parent,
            'distance' => $colorData->closest_palette_distance
        );
    }

    public function getRGB()
    {
        return array(
            'r' => intval($this->_red),
            'g' => intval($this->_green),
            'b' => intval($this->_blue)
        );
    }

    public function getHtmlCode()
    {
        return $this->_htmlCode;
    }

    public function getPercentage()
    {
        return floatval($this->_percentage);
    }

    public function getClosestPaletteColorName()
    {
        if ( !array_key_exists('name', $this->_closestPalette) )
        {
            return null;
        }
        return $this->_closestPalette['name'];
    }

    public function getClosestPaletteColorHtmlCode()
    {
        if ( !array_key_exists('html_code', $this->_closestPalette) )
        {
            return null;
        }
        return $this->_closestPalette['html_code'];
    }

    public function getClosestPaletteColorParent()
    {
        if ( !array_key_exists('parent', $this->_closestPalette) )
        {
            return null;
        }
        return $this->_closestPalette['parent'];
    }

    public function getClosestPaletteDistance()
    {
        if ( !array_key_exists('distance', $this->_closestPalette) )
        {
            return null;
        }
        return floatval($this->_closestPalette['distance']);
    }
}