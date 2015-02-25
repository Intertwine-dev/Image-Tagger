<?php

require_once (dirname(__FILE__).'/SDK/HttpClient.class.php');

class rekognition{
	// Api Key & Api Secrets
	private $parameters = array(
			'api_key' => 'YOUR-API-KEY', 
			'api_secret' => 'YOUR-API-SECRET', 
			'jobs' => 'scene_understanding_3'
		);
		
		
	public function getTag($url,$threshold=0,$verbose=false){
		try{
		$this->parameters['urls']=$url;
		$object_detection = new HttpClient('rekognition.com');
		$response = $object_detection->get("/func/api/", $this->parameters);
		$response = json_decode($object_detection->getContent());
		$result=array();
		$result['complete']=array();
		$result['tag']=array();
		if($verbose){
			echo "<table class='table table-hover'>";
			echo "<tr><td>Rekognition</td> <td> </td>  <td> </td> </tr>";
			echo "<tr class='active'><td>#</td><td>Tag </td><td> Score </td></tr>";
			$i=1;
		}
		
		if($response->usage->status!="Succeed."){
			$message="\n Date: ".date('Y-m-d H:i:s')." | Procedure: rekognition | Status: Error| StatusInfo: ".$response->usage->status;
			error_log($message,3,dirname(__FILE__).'/../log/error.log');			
			throw new Exception();
		
		}
		foreach($response->scene_understanding->matches as $key=>$value){
			$score=round($value->score*100);
			
			if($score>=$threshold){	
				if($verbose){
					echo "<tr><td>".$i++."</td><td> ".$value->tag." </td><td> ".$score."</td></tr>";
				}
				array_push($result['complete'],array('tag'=>$value->tag,'score'=>$score));
				array_push($result['tag'],$value->tag);
			}
		}
		if($verbose){
			echo "</table>";
		}
		return $result;
		}catch(Exception $e) {
			return $result;
		}
	}
	
}


?>
