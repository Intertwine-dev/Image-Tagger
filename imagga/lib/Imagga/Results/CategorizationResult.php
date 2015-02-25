<?php

namespace Imagga\Imagga\Results;


class CategorizationResult {

    private $_categories;
    private $_image;

    public static function fromJson($jsonString)
    {
        $jsonResult = json_decode($jsonString);

        $results = array();
        foreach ($jsonResult->results as $result)
        {
            $categorizationResult = new CategorizationResult();
            $categorizationResult->setImage($result->image);
            $categorizationResult->setCategories($result->categories);
            $results[] = $categorizationResult;
        }
        return $results;
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
    public function getCategories()
    {
        return $this->_categories;
    }

    public function getTopCategory()
    {
        return $this->_categories[0];
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->_image = $image;
    }

    /**
     * @param mixed $categories
     */
    public function setCategories($categories)
    {
        $this->_categories = array();
        foreach ($categories as $category)
        {
            $this->_categories[] = new Category($category->name, $category->confidence);
        }
    }


}