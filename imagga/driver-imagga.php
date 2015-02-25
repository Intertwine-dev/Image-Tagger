<?php
require_once dirname(__FILE__).'/lib/Imagga.php';

class imagga{
	// Api Key & Api Secrets
	
	private $config = array(
		'api_key' => 'YOUR-API-KEY',
		'api_secret' => 'YOUR-API-SECRET'
	);
	
	
	public function getTag($url,$threshold=0,$verbose=false){
		try{
			$client = new \Imagga\Imagga\Client($this->config['api_key'], $this->config['api_secret']);
			$response = $client->tagging($url);
			
			$result=array();
			$result['complete']=array();
			$result['tag']=array();
			
			if($verbose){
				echo "<table class='table table-hover'>";	
				echo "<tr ><td>Imagga</td><td> </td> <td> </td></tr>";
				echo "<tr class='active'><td>#</td><td> Tag </td><td> Score </td></tr>";
				$i=1;
			}
			
			if(!is_null($response->getErrors())){
				foreach ($response->getErrors() as $error){
					$message="\n Date: ".date('Y-m-d H:i:s')." | Procedure: imagga  | Status: ".$error->getStatusCode()." | StatusInfo: ".$error->getMessage();
					error_log($message,3,dirname(__FILE__).'/../log/error.log');			
				}
				throw new Exception();
			}
			foreach ($response->getResults() as $taggingResult)
			{
				foreach($taggingResult->getTagsLabels() as $key=>$value){
					$score=round($value['confidence']);
					if($score>=$threshold){
						if($verbose){
							echo "<tr><td>".$i++."</td><td> ".$value['label']." </td><td> ".$score."</td></tr>";
						}
						array_push($result['complete'],array('tag'=>$value['label'],'score'=>$score));
						array_push($result['tag'],$value1['label']);
					}
				}
			}
			if($verbose){	
				echo "</table>";
			}
			return $result;
		}catch(Exception $e){
			return $result;
		}
	}
}
?>