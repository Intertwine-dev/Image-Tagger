## Getting Started
***

All you have to do in order to get started using the Imagga PHP SDK is require `Imagga.php` from /lib.

    require_once "lib/Imagga.php"


## Calling the Tagging API
***

Now lets try to query the Tagging API and get the tags for the following image:

![Example Beach Photo](http://playground.imagga.com/static/img/example_photo.jpg)

To call the API we should use the `Client` class. Lets initialize it with our api key and secret and request the image tags:

    $photoUrl = "http://playground.imagga.com/static/img/example_photo.jpg";
    $client = new \Imagga\Imagga\Client($apiKey, $apiSecret);
    $taggingResponse = $client->tagging($photoUrl);
    
    if ($taggingResponse->getErrors())
    {
        // Display the errors
    }
    
The tagging result would be an array of `TaggingResult` objects. The number of results would equal the number of images you have sent for tagging.


We can now iterate through all the results and do something with the suggested tags:

    foreach ( $taggingResponse->getResults() as $result )
    {
        $imageUrl = $result->getImage(); // Get the image which has been sent for tagging
        
        // The tags would be sorted by confidence so the first tag would be the one with highest confidence
        $tags = $result->getTags(); // Returns an array of Tag objects
           
        // We can iterate through all tags
        foreach ($tags as $tag)
        {
            $tagLabel = $tag->getLabel();  // Get the label of the tag e.g. ocean
            $tagConfidence = $tag->getConfidence(); // Get the confidence of the most probable tag e.g. 57.9
            // Do something cool with the tag
        }
    }

And that's it. Now you know how to use the PHP SDK to gather tag suggestions from Imagga API.


## Using the Color Extraction API
***

    $client = new \Imagga\Imagga\Client($apiKey, $apiSecret);
    $colorExtractionResponse = $client->colorExtraction('http://playground.imagga.com/static/img/example_photo.jpg');
    
    foreach ($colorExtractionResponse->getResults() as $result)
    {
        $imageUrl = $result->getImage();
        
        $dominantColors = $result->getDominantColors();
        $objectColors = $result->getObjectColors();
        $backgroundColors = $result->getBackgroundColors();
        
        foreach ($dominantColors as $color)
        {
            $colorHex = $color->getHtmlCode();
            $rgb = $color->getRGB();
            $percentage = $color->getPercentage();
            $closestPaletteColorName = $color->getClosestPaletteColorName();
            $closestPaletteColorHex = $color->getClosestPaletteColorHtmlCode();
            $closestPaletteColorParent = $color->getClosestPaletteColorParent();
            $closestPaletteDistance = $color->getClosestPaletteDistance();
        }
    }

## Using the Cropping API
***
    
    $client = new \Imagga\Imagga\Client($apiKey, $apiSecret);
    $croppingResponse = $client->cropping('http://playground.imagga.com/static/img/example_photo.jpg');
    
    foreach ($croppingResponse->getResults() as $result)
    {
        $imageUrl = $result->getImage();
        $croppings = $result->getCroppings();
        
        foreach ($croppings as $cropping)
        {
            $coordinates = $cropping->getCoords(); // Associative array containing with keys x1, y1, x2, y2
            $resolution = $cropping->getResolution(); // Associative array with width and height keys
        }
    }
    

## Using the Categorization API
***

    $client = new \Imagga\Imagga\Client($apiKey, $apiSecret);
    $categorizationResponse = $client->categorization(http://playground.imagga.com/static/img/example_photo.jpg);
    
    foreach ( $categorizationResponse->getResults() as $result )
    {   
        $imageUrl = $result->getImage();
        $topCategory = $result->getTopCategory();
        $categories = $result->getCategories();
        
        foreach ($categories as $category)
        {
            $categoryName = $category->getName();
            $categoryConfidence = $category->getConfidence();
        }
    }
    
    
## Handling Errors
***
    
    $errors = $response->getErrors();
    
    // $errors would be null if there aren't any errors, otherwise it will be array of Error objects
    if ( $errors )
    {
        foreach ( $errors as $error )
        {
            echo 'Error: ' . $error->getMessage() . ', status code: ' . $error->getStatusCode();
        }
    }
