<?php
require_once (dirname(__FILE__).'/alchemy/driver-alchemy.php');
require_once (dirname(__FILE__).'/imagga/driver-imagga.php');
require_once (dirname(__FILE__).'/rekognition/driver-rekognition.php');
/* Version 0.1, 23 02 2015 - Salvatore Imparato ( https://github.com/semaxa )

*/
class ImageTagger{
	private $response=array();
	
	public function getTag($url,$config=array('threshold'=>10,'verbose'=>false,'report'=>true,'json'=>true)){
		try{
			if(!isset($config['threshold'])){
//				default:
				$config['threshold']=10;
			}
			if(!isset($config['verbose'])){
//				default:
				$config['verbose']=false;
			}
			if(!isset($config['json'])){
//				default:
				$config['json']=true;
			}
			if(!isset($config['report'])){
//				default:
				$config['report']=true;
			}
			
			$a=new alchemy();
			$i=new imagga();
			$r=new rekognition();
			
			$this->response['url']=$url;;
			$this->response['error']=false;
			$code=$this->checkRemoteFile($url);
			if($code!=200){
				throw new Exception($this->StatusCodeDefinition($code),$code);
			}
			
			if($config['verbose'])
				echo "<div style='width:550px;'># <br><img width='300px' src='".$url."'/> <br>";
			
			$resIma=$i->getTag($url,$config['threshold'],$config['verbose']);
			$resAlc=$a->getTag($url,$config['threshold'],$config['verbose']);
			$resRek=$r->getTag($url,$config['threshold'],$config['verbose']);
			
			$tag=array_merge($resAlc['complete'],$resIma['complete'],$resRek['complete']);
			
			$tag=$this->unique($tag);
			
			$tag=$this->array_msort($tag,array('score'=>SORT_DESC));
			
			if($config['verbose'])
				$this->printResults($tag);
			$this->response['imageKeywords']=$tag;
			$this->response['success']=true;
			
		}catch(Exception $e){
			$this->response['success']=false;
			$this->response['error']=array('message'=>$e->getMessage(),'code'=>$e->getCode());
		}
		
		if($config['report'])
			$this->report($this->response);
		if($config['json'])
			$this->response=json_encode($this->response);
			
		return $this->response;	
	}
	
	public function report(&$res){
		if($res['success'])
			$message="\n Date: ".date('Y-m-d H:i:s')." | success: ".(($res['success'])?'true ':'false')." | url: ".$res['url'];
		else{
			$message="\n Date: ".date('Y-m-d H:i:s')." | success: ".(($res['success'])?'true ':'false')." | url: ".$res['url']." Error Code: ".$res['error']['code']." Error Message: ".$res['error']['message'];			
		}
		error_log($message,3,dirname(__FILE__).'/log/report.log');
		
	}
	
	public function unique($array){
		$temp=array();
		$temp[0]=$array[0];
		foreach($array as $key => $value ){
			$sem=true;
			foreach($temp as $key1 =>$value1){
				if($value1['tag']===$value['tag']){
					if($value1['score']<=$value['score']){
						$temp[$key1]=$array[$key];
					}
					$sem=false;
					break;
				}
			}
			if($sem){
				array_push($temp,$array[$key]);
			}
		}
		return $temp;
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
	public function checkRemoteFile($url){
		$ch = curl_init($url);
		
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_exec($ch);
		$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		// $retcode >= 400 -> not found,$retcode = 200, found.
		curl_close($ch);
		return $retcode;
	}
	public function printResults($elements){
		echo "<div style='width:550px;'><table class='table table-hover ' >";
		echo "<tr><td> Results </td><td>	</td><td>  </td></tr>";
		echo "<tr class='active'><td>#</td><td> Tag </td><td> Score </td></tr>";
		$i=1;
		foreach($elements as $key => $value){
			echo "<tr><td>".$i++." </td><td>".$value['tag']." </td><td>   ".$value['score']."</td></tr>";
		}
		
		echo "</table></div>";	
	}
	public function StatusCodeDefinition($code){
		if ($code !== NULL) {
			
			switch ($code) {
				case 100: $text = 'Continue'; break;
				case 101: $text = 'Switching Protocols'; break;
				case 200: $text = 'OK'; break;
				case 201: $text = 'Created'; break;
				case 202: $text = 'Accepted'; break;
				case 203: $text = 'Non-Authoritative Information'; break;
				case 204: $text = 'No Content'; break;
				case 205: $text = 'Reset Content'; break;
				case 206: $text = 'Partial Content'; break;
				case 300: $text = 'Multiple Choices'; break;
				case 301: $text = 'Moved Permanently'; break;
				case 302: $text = 'Moved Temporarily'; break;
				case 303: $text = 'See Other'; break;
				case 304: $text = 'Not Modified'; break;
				case 305: $text = 'Use Proxy'; break;
				case 400: $text = 'Bad Request'; break;
				case 401: $text = 'Unauthorized'; break;
				case 402: $text = 'Payment Required'; break;
				case 403: $text = 'Forbidden'; break;
				case 404: $text = 'Not Found'; break;
				case 405: $text = 'Method Not Allowed'; break;
				case 406: $text = 'Not Acceptable'; break;
				case 407: $text = 'Proxy Authentication Required'; break;
				case 408: $text = 'Request Time-out'; break;
				case 409: $text = 'Conflict'; break;
				case 410: $text = 'Gone'; break;
				case 411: $text = 'Length Required'; break;
				case 412: $text = 'Precondition Failed'; break;
				case 413: $text = 'Request Entity Too Large'; break;
				case 414: $text = 'Request-URI Too Large'; break;
				case 415: $text = 'Unsupported Media Type'; break;
				case 500: $text = 'Internal Server Error'; break;
				case 501: $text = 'Not Implemented'; break;
				case 502: $text = 'Bad Gateway'; break;
				case 503: $text = 'Service Unavailable'; break;
				case 504: $text = 'Gateway Time-out'; break;
				case 505: $text = 'HTTP Version not supported'; break;
				default:
					$text=('Unknown http status code "' . htmlentities($code) . '"');
				break;
			}
		}
		return $text;
	}
	
}

?>