<?php

require_once (dirname(__FILE__).'/ImageTagger.php');

$url='http://wallmanner.com/wp-content/uploads/2014/09/Beach-Girl.jpg';

$test= new ImageTagger();

var_dump($test->getTag($url,$config));

/*
 $config=array('verbose'=>true);
 var_dump($test->getTag($url,$config));
*/


?>