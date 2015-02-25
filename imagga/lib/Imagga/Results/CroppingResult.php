<?php

namespace Imagga\Imagga\Results;


class CroppingResult {

    private $_image;
    private $_croppings;

    /**
     * @param $jsonString
     * @return array|null
     */
    public static function fromJson($jsonString)
    {
        $jsonResult = json_decode($jsonString);

        if ( !$jsonResult )
        {
            return null;
        }

        $results = array();
        foreach ($jsonResult->results as $result)
        {
            $croppingResult = new CroppingResult();
            $croppingResult->setCroppings($result->croppings);
            $croppingResult->setImage($result->image);
            $results[] = $croppingResult;
        }
        return $results;
    }

    /**
     * @param mixed $croppings
     */
    public function setCroppings($croppings)
    {
        $this->_croppings = array();
        foreach ($croppings as $cropping)
        {
            $this->_croppings[] = new Cropping($cropping);
        }
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->_image = $image;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->_image;
    }

    /**
     * @return mixed
     */
    public function getCroppings()
    {
        return $this->_croppings;
    }

}