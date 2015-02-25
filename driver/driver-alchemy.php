<?php
require_once (dirname(__FILE__).'/../alchemy/alchemyapi.php');
require_once (dirname(__FILE__).'/../config/config.php');
class alchemy{
	
	// Api Key & Api Secrets
	private $_api_key=ApiKeyAlchemy;
	
	public function getTag($url,$threshold=0,$verbose=false){
		try{
			
		$alchemyapi = new AlchemyAPI(ApiKeyAlchemy);
		$response=array();
		$response=$alchemyapi->image_keywords('url', $url, array('extractMode'=>'always-infer','forceShowAll'=>1));
		$result=array();
		$result['complete']=array();
		$result['tag']=array();
		if($verbose){
			echo "<table class='table table-hover ' >";
			echo "<tr ><td>Alchemy</td><td> </td> <td> </td></tr>";
			echo "<tr class='active'><td>#</td><td> Tag </td> <td> Score </td></tr>";
			$i=1;
		}
		if($response['status']!="OK"){
			$message="\n Date: ".date('Y-m-d H:i:s')." | Procedure: alchemy | Status: ".$response['status']." | StatusInfo: ".$response['statusInfo'];
			error_log($message,3,dirname(__FILE__).'/../log/error.log');			
			throw new Exception();
		}
			
		foreach($response['imageKeywords'] as $key=>$value){
			$score=round($value['score']*100);
			if($score>=$threshold){
				if($verbose){
					echo "<tr><td> ".$i++." </td><td>".$value['text']."</td><td>".$score."</td></tr>";
				}	
				array_push($result['complete'],array('tag'=>$value['text'],'score'=>$score));
				array_push($result['tag'],$value['text']);
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
	
	public function array_msort($array, $cols) { 
		$colarr = array(); 
		foreach ($cols as $col => $order) { 
			$colarr[$col] = array(); 
			foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); } 
		} 
		$eval = 'array_multisort('; 
		foreach ($cols as $col => $order) { 
			$eval .= '$colarr[\''.$col.'\'],'.$order.','; 
		} 
		$eval = substr($eval,0,-1).');'; 
		eval($eval); 
		$ret = array(); 
		foreach ($colarr as $col => $arr) { 
			foreach ($arr as $k => $v) { 
				$k = substr($k,1); 
				if (!isset($ret[$k])) $ret[$k] = $array[$k]; 
				$ret[$k][$col] = $array[$k][$col]; 
			} 
		} 
		return $ret; 
	} 
}


?>