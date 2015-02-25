<?php

namespace Imagga\Imagga\Results;


class TaggingResult {

	private $_imageId;
	private $_tags;

	public function __construct()
	{
		$this->tags = array();
	}

    /**
     * @param $jsonString string
     * @return array
     * @throws \Exception
     */
    public static function fromJson($jsonString)
	{
        if (!is_string($jsonString))
        {
            throw new \Exception('Expected JSON string as a first parameter.');
        }

		$jsonResult = json_decode($jsonString);

        if ( !$jsonResult )
        {
            throw new \Exception('The specified string cannot be parsed as JSON.');
        }

		$results = array();
		if ( isset($jsonResult->results) )
        {
            foreach ($jsonResult->results as $result)
            {
                $taggingResult = new TaggingResult();
                $taggingResult->setImageId($result->image);
                $taggingResult->setTags($result->tags);
                $results[] = $taggingResult;
            }
        }
		return $results;
	}

	public function setImageId($imageId)
	{
		$this->_imageId = $imageId;
	}

    public function getImage()
    {
        return $this->_imageId;
    }

	public function setTags($tagsArray)
	{
		foreach ($tagsArray as $tag)
		{
			$this->_tags[] = new Tag($tag->tag, $tag->confidence);
		}
		usort($this->_tags, array("\\Imagga\\Imagga\\Results\\Tag", "compare"));
    }

	public function getTags()
	{
		return $this->_tags;
	}

    public function getTagsLabels()
	{
		$tagsLabels = array();
		foreach ($this->_tags as $tag)
		{
//			$tagsLabels[] = $tag->getLabel();
			array_push($tagsLabels,array('label'=>$tag->getLabel(),'confidence'=>$tag->getConfidence()));
		}
		return $tagsLabels;
	}

	public function top10()
	{
		return array_slice($this->_tags, 0, 10);
	}
}