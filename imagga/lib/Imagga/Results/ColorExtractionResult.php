<?php

namespace Imagga\Imagga\Results;

class ColorExtractionResult
{

    private $_image;

    private $_backgroundColors;
    private $_foregroundColors;
    private $_dominantColors;

    private $_colorPercentageThreshold;
    private $_colorVariance;
    private $_objectPercentage;

    public static function fromJson($jsonString)
    {
        $jsonResult = json_decode($jsonString);
        $results = array();
        foreach ($jsonResult->results as $result) {
            $colorExtractionResult = new ColorExtractionResult();
            $colorExtractionResult->setImage($result->image);
            $colorExtractionResult->setBackgroundColors($result->info->background_colors);
            $colorExtractionResult->setObjectColors($result->info->foreground_colors);
            $colorExtractionResult->setDominantColors($result->info->image_colors);
            $colorExtractionResult->setColorVariance($result->info->color_variance);
            $colorExtractionResult->setColorPercentageThreshold($result->info->color_percent_threshold);
            $colorExtractionResult->setObjectPercentage($result->info->object_percentage);
            $results[] = $colorExtractionResult;
        }
        return $results;
    }

    /**
     * @return array of Color objects
     */
    public function getBackgroundColors()
    {
        return $this->_backgroundColors;
    }

    /**
     * @return array of Color objects
     */
    public function getObjectColors()
    {
        return $this->_foregroundColors;
    }

    /**
     * @return array of Color objects
     */
    public function getDominantColors()
    {
        return $this->_dominantColors;
    }

    /**
     * @return float
     */
    public function getObjectPercentage()
    {
        return $this->_objectPercentage;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->_image = $image;
    }

    /**
     * @param mixed $backgroundColors
     */
    public function setBackgroundColors($backgroundColors)
    {
        $this->_backgroundColors = array();
        foreach ($backgroundColors as $colorInfo)
        {
            $this->_backgroundColors[] = new Color($colorInfo);
        }
    }

    /**
     * @param mixed $foregroundColors
     */
    public function setObjectColors($foregroundColors)
    {
        $this->_foregroundColors = array();
        foreach ($foregroundColors as $colorInfo)
        {
            $this->_foregroundColors[] = new Color($colorInfo);
        }
    }

    /**
     * @param mixed $dominantColors
     */
    public function setDominantColors($dominantColors)
    {
        $this->_dominantColors = array();
        foreach ($dominantColors as $colorInfo)
        {
            $this->_dominantColors[] = new Color($colorInfo);
        }
    }

    /**
     * @param $colorPercentageThreshold
     */
    public function setColorPercentageThreshold($colorPercentageThreshold)
    {
        $this->_colorPercentageThreshold = floatval($colorPercentageThreshold);
    }

    /**
     * @param mixed $colorVariance
     */
    public function setColorVariance($colorVariance)
    {
        $this->_colorVariance = floatval($colorVariance);
    }

    /**
     * @return mixed
     */
    public function getColorVariance()
    {
        return $this->_colorVariance;
    }

    /**
     * @param mixed $objectPercentage
     */
    public function setObjectPercentage($objectPercentage)
    {
        $this->_objectPercentage = floatval($objectPercentage);
    }

    /**
     * @return mixed
     */
    public function getColorPercentageThreshold()
    {
        return $this->_colorPercentageThreshold;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->_image;
    }

}