<?php

require_once (dirname(__FILE__).'/ImageTagger.php');

require_once (dirname(__FILE__).'/test-url.php');
$test= new ImageTagger();

foreach($test_url as $key => $value){
	var_dump($test->getTag($value));
}

/*
 $config=array('verbose'=>true);
 
 foreach($test_url as $key => $value){
 	$test->getTag($url,$config);
 }
*/
?>