<?php

namespace Imagga\Imagga\Results;


class Content {

    private $_contentId;
    private $_fileName;

    public function __construct($contentId, $fileName)
    {
        $this->_contentId = $contentId;
        $this->_fileName = $fileName;
    }

    public static function fromJson($jsonString)
    {
        $jsonResult = json_decode($jsonString);

        $results = array();
        foreach ( $jsonResult->uploaded as $uploaded )
        {
            $results[] = new Content($uploaded->id, $uploaded->filename);
        }
        return $results;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_contentId;
    }

    /**
     * @return mixed
     */
    public function getFileName()
    {
        return $this->_fileName;
    }

}