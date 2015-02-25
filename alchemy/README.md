# alchemyapi_php #

A software development kit (sdk) for AlchemyAPI using PHP



## AlchemyAPI ##

AlchemyAPI offers artificial intelligence as a service. We teach computers to learn how to read and see, and apply our technology to text analysis and image recognition through a cloud-based API. Our customers use AlchemyAPI to transform their unstructured content such as blog posts, news articles, social media posts and images into much more useful structured data. 

AlchemyAPI is a tech startup located in downtown Denver, Colorado. As the world's most popular text analysis service, AlchemyAPI serves over 3.5 billion monthly API requests to over 35,000 developers. To enable our services, we use artificial intelligence, machine learning, neural networks, natural language processing and massive-scale web crawling. Our technology powers use cases in a variety of industry verticals, including social media monitoring, business intelligence, content recommendations, financial trading and targeted advertising.

More information at: http://www.alchemyapi.com



## API Key ##

To use AlchemyAPI, you'll need to obtain an API key and attach that key to all requests. If you do not already have a key, please visit: http://www.alchemyapi.com/api/register.html



## Demo ##

To use our interactive web demo, which includes access to all of AlchemyAPI's text analysis functions, please visit: http://www.alchemyapi.com/products/demo. You can use the demo to get a better understanding of how the text analysis functions work, and run some of your sample data through it to see what kind of results you can expect. The demo calls the exact same API functions as this SDK.



## Getting Started with the PHP SDK ##

To get started and run the example, simply:

	git clone https://github.com/AlchemyAPI/alchemyapi_php.git
	cd alchemyapi_php
	php alchemyapi.php YOUR_API_KEY
	php example.php


Just replace YOUR_API_KEY with your 40 character API key from AlchemyAPI, and you should be good to go.

	

## Using the PHP SDK ##

This SDK makes accessing all of AlchemyAPI's text analysis functions with PHP easy. The following is a list of the available functions and how to use them within your application. For working code examples of these, please checkout the example code and run the example as described in the "Getting Started with the PHP SDK" section above. 



### Creating the AlchemyAPI object ###

To use each function, you must first create an AlchemyAPI object. The easiest way to do this is:

	<?php
		require_once 'alchemyapi.php';
		$alchemyapi = new AlchemyAPI();
	?>

You can now use this object to access AlchemyAPI's text analysis functions. 

### Image Keywords ###

The Image Keywords API tags an image identified by a URL or image data posted in the body of an html request. For more high-level information on AlchemyAPI's Image keywords API, please visit: http://www.alchemyapi.com/products/features/image-tagging. For more technical information, please refer to the docs: http://www.alchemyapi.com/api/image-tagging.

To extract entities, use:

	<?php
		$response = $alchemyapi->image_keywords(FLAVOR, DATA, OPTIONS);
	?>

Where FLAVOR can be 'url' or 'image' DATA is your url or uri-argument encoded image data, and OPTIONS is an array containing the optional parameters to modify the behavior of the call.


**Options**

The following options are available for this call. To use, include your desired options into an array and pass it as the OPTIONS parameter in the call. 

-	imagePostMode -> (only applicable to image flavor)
-		not-raw :  pass an unencoded image file with "image=URI_ENCODED_DATA"
-		raw     :  pass an unencoded image file using POST
-	extractMode -> 
-     always-infer    :  (more CPU intensive, more accurate)
-     trust-metadata  :  (less CPU intensive, less accurate) (default)
-     only-metadata   :  (even less CPU intensive, less accurate)

**Parsing**

To parse the results, simply step through the response structure that is detailed in the docs. For example, here's how to print the keyword and score of each tagged image:

	<?php
		$response = $alchemyapi->image_keywords('url','http://www.lolcats.com/images/u/08/50/lolcatsdotcomur5dhkw464f8hb16.jpg', null);
		foreach ($response['imageKeywords'] as $imagekeyword) {
			echo 'keyword: ', $imagekeyword['text'], PHP_EOL;
			echo 'score: ', $imagekeyword['score'], PHP_EOL;
		}
	?>

This should print out:
	
	keyword: cat
	score: 0.999697
	keyword: kitten
	score: 0.942676

	


### Entities ###

The entity extraction API identifies the proper nouns in your text, HTML or URL content. These are the named people, companies, organizations and locations. For more high-level information on AlchemyAPI's entity extraction API, please visit: http://www.alchemyapi.com/products/features/entity-extraction. For more technical information, please refer to the docs: http://www.alchemyapi.com/api/entity-extraction.

To extract entities, use:

	<?php
		$response = $alchemyapi->entities(FLAVOR, DATA, OPTIONS);
	?>

Where FLAVOR can be 'text','html', or 'url', DATA is your text, html or url content, and OPTIONS is an array containing the optional parameters to modify the behavior of the call.


**Options**

The following options are available for this call. To use, include your desired options into an array and pass it as the OPTIONS parameter in the call. 

- disambiguate -> disambiguate entities (i.e. Apple the company vs. apple the fruit). 0: disabled, 1: enabled (default)
- linkedData -> include linked data on disambiguated entities. 0: disabled, 1: enabled (default) 
- coreference -> resolve coreferences (i.e. the pronouns that correspond to named entities). 0: disabled, 1: enabled (default)
- quotations -> extract quotations by entities. 0: disabled (default), 1: enabled.
- sentiment -> analyze sentiment for each entity. 0: disabled (default), 1: enabled. Requires 1 additional API transction if enabled.
- showSourceText -> 0: disabled (default), 1: enabled 
- maxRetrieve -> the maximum number of entities to retrieve (default: 50)


**Parsing**

To parse the results, simply step through the response structure that is detailed in the docs. For example, here's how to print the text and type of each extracted entity:

	<?php
		$response = $alchemyapi->entities('text','I like Ford cars better than icecream.', null);
		foreach ($response['entities'] as $entity) {
			echo 'entity: ', $entity['text'], PHP_EOL;
			echo 'type: ', $entity['type'], PHP_EOL;
		}
	?>

This should print out:
	
	entity: Ford
	type: Company

	

### Keywords ###

The keyword extraction API identifies the important terms in your text, HTML or URL content. These are known by several names, but keywords, tags and terms are the most common. For more high-level information on AlchemyAPI's keyword extraction API, please visit: http://www.alchemyapi.com/products/features/keyword-extraction. For more technical information, please refer to the docs: http://www.alchemyapi.com/api/keyword-extraction.

To extract keywords, use:

	<?php
		$response = $alchemyapi->keywords(FLAVOR, DATA, OPTIONS);
	?>

Where FLAVOR can be 'text','html', or 'url', DATA is your text, html or url content, and OPTIONS is an array containing the optional parameters to modify the behavior of the call.


**Options**

The following options are available for this call. To use, include your desired options into an array and pass it as the OPTIONS parameter in the call. 

- keywordExtractMode -> normal (default), strict
- sentiment -> analyze sentiment for each keyword. 0: disabled (default), 1: enabled. Requires 1 additional API transaction if enabled.
- showSourceText -> 0: disabled (default), 1: enabled.
- maxRetrieve -> the max number of keywords returned (default: 50)


**Parsing**

To parse the results, simply step through the response structure that is detailed in the docs. For example, here's how to print the text and relevance of each extracted keyword:

	<?php
		$response = $alchemyapi->keywords('text', 'Bunnies are nice but sometimes robots are evil', array('sentiment'=>1);
		foreach ($response['keywords'] as $keyword) {
			echo 'keyword: ', $keyword['text'], PHP_EOL;
			echo 'relevance: ', $keyword['relevance'], PHP_EOL;
			echo 'sentiment: ', $keyword['sentiment']['type'], PHP_EOL;
		}
	?>

This should print out:

	keyword: Bunnies
	relevance: 0.981751
	sentiment: positive
	keyword: robots
	relevance: 0.832378
	sentiment: negative



### Concepts ###

The concept tagging API identifies the high-level themes of your text, HTML or URL content. Unlike keywords, the concepts do not have to be specifically mentioned in the content. For more high-level information on AlchemyAPI's concept tagging API, please visit: http://www.alchemyapi.com/products/features/concept-tagging. For more technical information, please refer to the docs: http://www.alchemyapi.com/api/concept-tagging.

To tag concepts, use:

	<?php
		$response = $alchemyapi->concepts(FLAVOR, DATA, OPTIONS);
	?>

Where FLAVOR can be 'text','html', or 'url', DATA is your text, html or url content, and OPTIONS is an array containing the optional parameters to modify the behavior of the call.


**Options**

The following options are available for this call. To use, include your desired options into an array and pass it as the OPTIONS parameter in the call. 

- maxRetrieve -> the maximum number of concepts to retrieve (default: 8)
- linkedData -> include linked data, 0: disabled, 1: enabled (default)
- showSourceText -> 0:disabled (default), 1: enabled


**Parsing**

To parse the results, simply step through the response structure that is detailed in the docs. For example, here's how to print the text and relevance of each concept:

	<?php
		$response = $alchemyapi->concepts('text','Today for lunch I ate two apples and one strawberry.', null);
		foreach ($response['concepts'] as $concept) {
			echo 'concept: ', $concept['text'], PHP_EOL;
			echo 'relevance: ', $concept['relevance'], PHP_EOL;
		}
	?>

This should print out:

	concept: Fruit
	relevance: 0.865419



### Sentiment ###

The sentiment analysis API calculates the document-level sentiment of your text, HTML or URL content. Sentiment determines whether the content is positive or negative, and can be calculated for the entire document, a user-specified target, or at the keyword, entity or relational level. This API call is only for document sentiment. To get keyword, entity or relational sentiment, set the OPTION parameter {'sentiment':1} in the respective call. Targeted sentiment is handled by a seperate call as well. For more high-level information on AlchemyAPI's sentiment analysis API, please visit: http://www.alchemyapi.com/products/features/sentiment-analysis. For more technical information, please refer to the docs: http://www.alchemyapi.com/api/sentiment-analysis.

To calculate document-level sentiment, use:

	<?php
		$response = $alchemyapi->sentiment(FLAVOR, DATA, OPTIONS);
	?>

Where FLAVOR can be 'text','html', or 'url', DATA is your text, html or url content, and OPTIONS is an array containing the optional parameters to modify the behavior of the call.


**Options** 

The following options are available for this call. To use, include your desired options into an array and pass it as the OPTIONS parameter in the call. 

- showSourceText -> 0: disabled (default), 1: enabled


**Parsing**

To parse the results, simply step through the response structure that is detailed in the docs. For example, here's how to print the type and score of the document sentiment:

	<?php
		$response = $alchemyapi->sentiment('text','I really like chocolate cake.', null);
		echo 'sentiment: ', $response['docSentiment']['type'], PHP_EOL;
		echo 'score: ', $response['docSentiment']['score'], PHP_EOL;
	?>

This should print out:

	sentiment: positive
	score: 0.631595


NOTE: The sentiment type can be either positive, negative or neutral. The score is only returned for positive and negative sentiment, and is not included in the response structure for neutral sentiment.



### Targeted Sentiment ###

The targeted sentiment analysis API calculates the document-level sentiment of your text, HTML or URL content in relation to a specified target. The targets are typically brand names, product names, specific features and similar. Please see the sentiment section above for more info about sentiment analysis.

To calculate targeted sentiment for an entire document, use:

	<?php
		$response = $alchemyapi->sentiment_targeted(FLAVOR, DATA, TARGET, OPTIONS);
	?>

Where FLAVOR can be 'text','html', or 'url', DATA is your text, html or url content, TARGET is the user-specified target, and OPTIONS is an array containing the optional parameters to modify the behavior of the call. 

**Options**

The following options are available for this call. To use, include your desired options into an array and pass it as the OPTIONS parameter in the call. 

- showSourceText -> 0: disabled, 1: enabled


**Parsing** 

To parse the results, simply step through the response structure that is detailed in the docs. For example, here's how to print the type and score of the targeted sentiment:

	<?php
		$response = $alchemyapi->sentiment_targeted('text','My new phones screen is awesome, but the battery life is really bad.','battery life', null);
		echo 'sentiment: ', $response['docSentiment']['type'], PHP_EOL;
		echo 'relevance: ', $response['docSentiment']['score'], PHP_EOL;
	?>

This should print out:

	sentiment: negative
	relevance: -0.128125


NOTE: The sentiment type can be either positive, negative or neutral. The score is only returned for positive and negative sentiment, and is not included in the response structure for neutral sentiment.



### Text ###

The text extraction API pulls out the important content from your HTML or URL content and leaves the unimportant content like navigation and ads behind. For more high-level information on AlchemyAPI's text extraction API, please visit: http://www.alchemyapi.com/products/features/text-extraction. For more technical information, please refer to the docs: http://www.alchemyapi.com/api/text-extraction.

To extract the text, use:

	<?php
		$response = $alchemyapi->text(FLAVOR, DATA, OPTIONS);
	?>

Where FLAVOR can be 'html' or 'url', DATA is your html or url content and OPTIONS is an array containing the optional parameters to modify the behavior of the call. 


**Options**

The following options are available for this call. To use, include your desired options into an array and pass it as the OPTIONS parameter in the call. 

- useMetadata -> utilize meta description data, 0: disabled, 1: enabled (default)
- extractLinks -> include links, 0: disabled (default), 1: enabled.


**Parsing**

To parse the results, simply step through the response structure that is detailed in the docs. For example, here's how to print the extracted text:

	<?php
		$response = $alchemyapi->text('url','http://www.alchemyapi.com/products/features/text-extraction/',null);
		echo 'text: ', $response['text'], PHP_EOL;
	?>

This should print out:

	text:  ... the text from the text extraction webpage ...


The above extracts the "cleaned" text. If you would like to get the raw text instead, use:

	<?php
		$response = $alchemyapi->text_raw(FLAVOR, DATA, OPTIONS);
	?>

The parameters and parsing are the same as for the cleaned text call.



### Author ###

The author extraction API identifies the author of your HTML or URL content. For more high-level information on AlchemyAPI's author extraction API, please visit: http://www.alchemyapi.com/products/features/author-extraction. For more technical information, please refer to the docs: http://www.alchemyapi.com/api/author-extraction.

To extract the author, use:

	<?php
		$response = $alchemyapi->author(FLAVOR, DATA, OPTIONS);
	?>

Where FLAVOR can be 'html' or 'url', DATA is your html or url content, and OPTIONS is an array containing the optional parameters to modify the behavior of the call. 


**Options**

There are no available options for this call.


**Parsing**

To parse the results, simply step through the response structure that is detailed in the docs. For example, here's how to print the author:

	<?php
		$response = $alchemyapi->author('url','http://www.grantland.com/story/_/id/9566213/bill-barnwell-puts-2013-detroit-vikings-numbers-test',null);

		echo 'author: ', $response['author'], PHP_EOL;
	?>

This should print out:
	
	author: Bill Barnwell



### Language ###

The language detection API identifies the language of your text, HTML or URL content. It currently supports detection of over 97 languages. For more high-level information on AlchemyAPI's language detection API, please visit: http://www.alchemyapi.com/products/features/language-detection. For more technical information, please refer to the docs: http://www.alchemyapi.com/api/language-detection.

To detect the language, use:

	<?php
		$response = $alchemyapi->language(FLAVOR, DATA, OPTIONS);
	?>

Where FLAVOR can be 'text', 'html' or 'url', DATA is your text, html or url content, and OPTIONS is an array containing the optional parameters to modify the behavior of the call.


**Options**

There are no available options for this call.


**Parsing**

To parse the results, simply step through the response structure that is detailed in the docs. For example, here's how to print the language and ISO-639-1 code:

	<?php
		$response = $alchemyapi->language('text','donde estas el bano?', null);
		echo 'language: ', $response['language'], PHP_EOL;
		echo 'iso-639-1: ', $response['iso-639-1'], PHP_EOL;
	?>

This should print out:

	language: spanish
	iso-639-1: es



### Title ###

The title extraction API identifies the title of your HTML or URL content. For more high-level information on AlchemyAPI's title extraction API, please visit: http://www.alchemyapi.com/products/features/text-extraction. For more technical information, please refer to the docs: http://www.alchemyapi.com/api/text-extraction.

To extract the title, use:

	<?php
		$response = $alchemyapi->title(FLAVOR, DATA, OPTIONS);
	?>

Where FLAVOR can be 'html' or 'url', DATA is your html or url content, and OPTIONS is an array containing the optional parameters to modify the behavior of the call. 


**Options**

The following options are available for this call. To use, include your desired options into an array and pass it as the OPTIONS parameter in the call. 

- useMetadata -> utilize title info embedded in meta data, 0: disabled, 1: enabled (default) 


**Parsing**

To parse the results, simply step through the response structure that is detailed in the docs. For example, here's how to print the title:

	<?php
		$response = $alchemyapi->title('url','http://www.bloomberg.com/news/2013-08-13/florida-to-sue-georgia-in-u-s-supreme-court-over-water.html', null);
		echo 'title: ', $response['title'], PHP_EOL;
	?>

This should print out:

	title: Florida to Sue Georgia in U.S. Supreme Court Over Water - Bloomberg



### Relations ###

The relation extraction API identifies the Subject, Action, Object relations in your text, HTML or URL content. In addition to sentiment analysis, there are lots of options for this call, so be sure to check out the docs. For more high-level information on AlchemyAPI's relation extraction API, please visit: http://www.alchemyapi.com/products/features/relation-extraction. For more technical information, please refer to the docs: http://www.alchemyapi.com/api/relation-extraction.

To extract relations, use:

	<?php
		$response = $alchemyapi->relations(FLAVOR, DATA, OPTIONS);
	?>

Where FLAVOR can be 'text','html', or 'url', DATA is your text, html or url content, and OPTIONS is an array containing the optional parameters to modify the behavior of the call. 


**Options**

The following options are available for this call. To use, include your desired options into an array and pass it as the OPTIONS parameter in the call. 

- sentiment -> 0: disabled (default), 1: enabled. Requires one additional API transaction if enabled.
- keywords -> extract keywords from the subject and object. 0: disabled (default), 1: enabled. Requires one additional API transaction if enabled.
- entities -> extract entities from the subject and object. 0: disabled (default), 1: enabled. Requires one additional API transaction if enabled.
- requireEntities -> only extract relations that have entities. 0: disabled (default), 1: enabled.
- sentimentExcludeEntities -> exclude full entity name in sentiment analysis. 0: disabled, 1: enabled (default)
- disambiguate -> disambiguate entities (i.e. Apple the company vs. apple the fruit). 0: disabled, 1: enabled (default)
- linkedData -> include linked data with disambiguated entities. 0: disabled, 1: enabled (default).
- coreference -> resolve entity coreferences. 0: disabled, 1: enabled (default)  
- showSourceText -> 0: disabled (default), 1: enabled.
- maxRetrieve -> the maximum number of relations to extract (default: 50, max: 100)


**Parsing**

To parse the results, simply step through the response structure that is detailed in the docs. For example, here's how to print the subject, action and object of each extracted relation:

	<?php
		$response = $alchemyapi->relations('text','Bob broke my heart.',null);
		foreach ($response['relations'] as $relation) {
			if (array_key_exists('subject', $relation)) {
					echo 'Subject: ', $relation['subject']['text'], PHP_EOL;
			}

			if (array_key_exists('action', $relation)) {
					echo 'Action: ', $relation['action']['text'], PHP_EOL;
			}

			if (array_key_exists('object', $relation)) {
					echo 'Object: ', $relation['object']['text'], PHP_EOL;
			}
			echo PHP_EOL;
		}
	?>

This should print out:
	
	Subject: Bob
	Action: broke
	Object: my heart



### Category ###

The text categorization API identifies the high-level category of your text, HTML or URL content. The categories include values such as Arts & Entertainment, Business and Sports. For more high-level information on AlchemyAPI's text categorization API, please visit: http://www.alchemyapi.com/products/features/text-categorization. For more technical information, please refer to the docs: http://www.alchemyapi.com/api/text-categorization.

To detect the category, use:

	<?php
		$response = $alchemyapi->category(FLAVOR, DATA, OPTIONS);
	?>

Where FLAVOR can be 'text', 'html' or 'url', DATA is your text, html or url content, and OPTIONS is an array containing the optional parameters to modify the behavior of the call. 


**Options**

The following options are available for this call. To use, include your desired options into an array and pass it as the OPTIONS parameter in the call. 

- showSourceText -> 0: disabled (default), 1: enabled


**Parsing**

To parse the results, simply step through the response structure that is detailed in the docs. For example, here's how to print the category:

	<?php
		$response = $alchemyapi->category('url','http://www.washingtonpost.com/blogs/capital-weather-gang/wp/2013/08/14/d-c-area-forecast-ultra-nice-weather-dominates-next-few-days/', null);
		echo 'category: ', $response['category'], PHP_EOL;
	?>

This should print out:

	category: weather



### Feeds ###

The feed detection API extracts the RSS/ATOM feeds embedded in your HTML or URL content. For more high-level information on AlchemyAPI's feed detection API, please visit: http://www.alchemyapi.com/products/features/feed-detection. For more technical information, please refer to the docs: http://www.alchemyapi.com/api/feed-detection.

To detect the feeds, use:

	<?php
		$response = $alchemyapi->feeds(FLAVOR, DATA, OPTIONS);
	?>

Where FLAVOR can be 'html' or 'url', DATA is your html or url content, and OPTIONS is an array containing the optional parameters to modify the behavior of the call. 


**Options**

There are no available options for this call.


**Parsing**

To parse the results, simply step through the response structure that is detailed in the docs. For example, here's how to print the feeds:

	<?php
		$response = $alchemyapi->feeds('url','http://cnn.com', null);
		foreach ($response['feeds'] as $feed) {
			echo 'feed: ', $feed['feed'], PHP_EOL;
		}
	?>

This should print out:

	feed: http://rss.cnn.com/rss/cnn_topstories.rss
	feed: http://rss.cnn.com/rss/cnn_latest.rss



### Microformats ###

The microformat parsing API extracts the microformats embedded in your HTML or URL content. For more high-level information on AlchemyAPI's microformats parsing API, please visit: http://www.alchemyapi.com/products/features/microformats-parsing. For more technical information, please refer to the docs: http://www.alchemyapi.com/api/microformats-parsing.

To detect the microformats, use:

	<?php
		$response = $alchemyapi->microformats(FLAVOR, DATA, OPTIONS);
	?>

Where FLAVOR can be 'html' or 'url', DATA is your html or url content, and OPTIONS is an array containing the optional parameters to modify the behavior of the call. 


**Options**

There are no options available for this call.


**Parsing**

To parse the results, simply step through the response structure that is detailed in the docs. For example, here's how to print the microformats:

	<?php
		$response = $alchemyapi->microformats('url','http://semanticweb.com/semanticweb-com-%E2%80%9Cinnovation-spotlight%E2%80%9D-interview-with-elliot-turner-ceo-of-alchemyapi_b30913', null);
		foreach ($response['microformats'] as $microformat) {
			echo 'field: ', $microformat['field'], PHP_EOL;
			echo 'data: ', $microformat['data'], PHP_EOL;
		}
	?>

This should print out:

	field: RelTagLink
	data: http://semanticweb.com/tag/alchemyapi
	field: RelTag
	data: alchemyapi



