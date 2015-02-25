<?php
/*
* Copyright (C) 2013 Orbeus Inc.
*
* Licensed under the Apache License, Version 2.0 (the "License");
* you may not use this file except in compliance with the License.
* You may obtain a copy of the License at
*
*      http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS,
* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and
* limitations under the License.
*/
//  Author: Tianqiang Liu - tqliu@orbe.us

require_once $GLOBALS['REKOGNITION_ROOT'].'config.php';
require_once $GLOBALS['REKOGNITION_ROOT'].'Rekognition_Parser.php';
require_once $GLOBALS['REKOGNITION_ROOT'].'Rekognition_GUI.php';

/**
* Rekognition API.
* Typical usage is:
*  <code>
*   $rapiConfig = array();
*   $rapiConfig['api_key'] = $rekognition_api_key;
*   $rapiConfig['api_secret'] = $rekognition_api_secret;
*   $rapiConfig['jobs'] = $rekognition_jobs;
*   $rapiConfig['name_space'] = $rekognition_name_space;
*   $rapiConfig['user_id'] = $rekognition_user_id;
*   $rekognition = new Rekognition_API($rapiConfig);
*   $rekognition->GetMetadata($req, 
*         Rekognition_API::REQUEST_RAW, 
*         Rekognition_API::RETURN_JSON)
*  </code>
*/

class Rekognition_API {
  const REQUEST_UNDEFINED = 0;
  const REQUEST_URL = 1;
  const REQUEST_DIR = 2;
  const REQUEST_RAW = 3;
  
  const RETURN_JSON = 10;
  const RETURN_ARRAY = 11;
  const RETURN_PARSED = 12;
  
  /**
   * @static
   * @var Rekognition API Key: $api_key_
   */
  static $api_key_;

  /**
   * @static
   * @var Rekognition API secret: $api_secret_
   */
  static $api_secret_;

  /**
   * @static
   * @var Rekognition Namespace: $name_space_
   */
  static $name_space_;
  
  /**
   * @static
   * @var Rekognition User Id: $user_id_
   */
  static $user_id_;
  
  /**
   * @private
   * @var Rekognition_Parser Id: $parsed_obj_
   */
  private $parsed_obj_;
  

  public function __construct($config = array()) {
    self::$api_key_ = $config['api_key'];
    self::$api_secret_ = $config['api_secret'];
    self::$name_space_ = $config['name_space'];
    self::$user_id_ = $config['user_id'];
  }
  
   /**
   * Set API Key
   */
  public function SetApiKey($value){
     self::$api_key_ = $value;
  }
  
  /**
   * Set API Secret
   */
  public function SetApiSecret($value){
     self::$api_secret_ = $value;
  }
  
  /**
   * Set NameSpace
   */
  public function SetNameSpace($value){
     self::$name_space_ = $value;
  }
  
  /**
   * Set UserId
   */
  public function SetUserId($value){
     self::$user_id_ = $value;
  }
  
  public function RkImageResize($req,
                               $scale,
                               $request_mode = Rekognition_API::REQUEST_UNDEFINED
                               ) {
    if ($request_mode == 2) {
      $req = file_get_contents($req);
    }
    $im = imagecreatefromstring($req);
    $width = imagesx($im);
    $height = imagesy($im);
    $new_width = $scale * $width;
    $new_height = $scale * $height;
      
    $im_p = imagecreatetruecolor($new_width, $new_height);
    imagecopyresampled($im_p, $im, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    ob_start();
    imagejpeg($im_p);
    $new_img = ob_get_contents();
    ob_end_clean();
    imagedestroy($im_p);
    return $new_img;
  }
  
  /**
   * Request to Rekognition server and return results encoded using JSON or parsed object
   * @param string $req: Image data
   * @param string $request_mode: Data type of $req, could be path(Rekognition_API::REQUEST_DIR), url(Rekognition_API::REQUEST_URL) or raw data(Rekognition_API::REQUEST_RAW)
   * @param string $return_mode: JSON string(Rekognition_API::RETURN_JSON), array(Rekognition_API::RETURN_ARRAY) or the object parsed by Rekognition_Parser(Rekognition_API::RETURN_PARSED)
   * @return image metadata defined in $return_mode
   */
  public function RkFaceDetect($req, 
                               $scale,
                              $request_mode = Rekognition_API::REQUEST_UNDEFINED, 
                              $return_mode = Rekognition_API::RETURN_JSON){
    if(!$request_mode) {
      return 'Undefined request mode!';
    }
    $result = '';
    if($request_mode == 1){
      require_once $GLOBALS['REKOGNITION_ROOT'].'HttpClient.class.php';
      $parameters = array(
                  'api_key' => self::$api_key_, 
                  'api_secret' => self::$api_secret_, 
                  'jobs' => 'face_part_gender_emotion_age_glass_mouth',
                  'urls' => $req,
                  'name_space' => self::$name_space_,
                  'user_id' => self::$user_id_);
      $face_detection = new HttpClient('rekognition.com');
      $face_detection->setDebug(false);
      $response = $face_detection->get("/func/api/", $parameters);
      $result = $face_detection->getContent();
    }
    else {
      $ch = curl_init();    
      $im = $this->RkImageResize($req, $scale, $request_mode);  
      $data = array('api_key' => self::$api_key_, 
              'api_secret' => self::$api_secret_, 
              'jobs' => 'face_part_gender_emotion_age_glass_mouth',
              'base64' => base64_encode($im),
              'name_space' => self::$name_space_,
              'user_id' => self::$user_id_); 
      curl_setopt($ch, CURLOPT_URL, 'http://rekognition.com/func/api/');
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $result = curl_exec($ch);
      curl_close($ch);
    }
    $this->parsed_obj_ = new Rekognition_Parser($result);
 
    if($return_mode == 10) {
      return $result;
    }
    elseif($return_mode == 11) {
      return json_decode($result);
    }
    else {
      return $this->parsed_obj_;
    }
  }
  
  /**
   * Request to Rekognition server for face add task and return results encoded using JSON or parsed object
   * @param string $req: Image data
   * @param string $name: Face name tag
   * @param string $request_mode: Data type of $req, could be path(Rekognition_API::REQUEST_DIR), url(Rekognition_API::REQUEST_URL) or raw data(Rekognition_API::REQUEST_RAW)
   * @param string $return_mode: JSON string(Rekognition_API::RETURN_JSON), array(Rekognition_API::RETURN_ARRAY)
   * @return callback information defined in $return_mode
   */
  public function RkFaceAdd($req,
                            $name,
                            $scale,
                            $request_mode = Rekognition_API::REQUEST_UNDEFINED, 
                            $return_mode = Rekognition_API::RETURN_JSON) {
    if(!$request_mode) {
      return 'Undefined request mode!';
    }
    $result = '';
    if($request_mode == 1){
      require_once $GLOBALS['REKOGNITION_ROOT'].'HttpClient.class.php';
      $parameters = array(
                  'api_key' => self::$api_key_, 
                  'api_secret' => self::$api_secret_, 
                  'job_list' => 'face_add_['.$name.']',
                  'urls' => $req,
                  'name_space' => self::$name_space_,
                  'user_id' => self::$user_id_);
      $face_detection = new HttpClient('rekognition.com');
      $face_detection->setDebug(false);
      $response = $face_detection->get("/func/api/", $parameters);
      $result = $face_detection->getContent();
    }
    else {    
      $ch = curl_init();    
      $im = $this->RkImageResize($req, $scale, $request_mode);  
      $data = array('api_key' => self::$api_key_, 
              'api_secret' => self::$api_secret_, 
              'job_list' => 'face_add_['.$name.']',
              'base64' => base64_encode($im),
              'name_space' => self::$name_space_,
              'user_id' => self::$user_id_); 
      
      curl_setopt($ch, CURLOPT_URL, 'http://rekognition.com/func/api/');
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $result = curl_exec($ch);
      curl_close($ch);
    }
 
    if($return_mode == 11) {
      return json_decode($result);
    }
    else {
      return $result;
    }
  }
  
  /**
   * Request to Rekognition server for face train task and return results encoded using JSON or parsed object
   * @param string $return_mode: JSON string(Rekognition_API::RETURN_JSON), array(Rekognition_API::RETURN_ARRAY)
   * @return callback information defined in $return_mode
   */
  public function RkFaceTrain($return_mode = Rekognition_API::RETURN_JSON) {
    $ch = curl_init();
    $data = array('api_key' => self::$api_key_, 
           'api_secret' => self::$api_secret_, 
           'jobs' => 'face_train',
           'name_space' => self::$name_space_,
           'user_id' => self::$user_id_); 
    curl_setopt($ch, CURLOPT_URL, 'http://rekognition.com/func/api/');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
 
    if($return_mode == 11) {
      return json_decode($result);
    }
    else {
      return $result;
    }
  }
  
  /**
   * Request to Rekognition server for face crawl task and return results encoded using JSON or parsed object
   * @param string $access_token: Facebook access token
   * @param string $fb_id: Facebook user id
   * @param string $friend_id: Facebook friend id
   * @param string $return_mode: JSON string(Rekognition_API::RETURN_JSON), array(Rekognition_API::RETURN_ARRAY)
   * @return callback information defined in $return_mode
   */
  public function RkFaceCrawl($access_token, 
                              $fb_id, 
                              $friend_id,
                              $return_mode = Rekognition_API::RETURN_JSON) {
    require_once $GLOBALS['REKOGNITION_ROOT'].'HttpClient.class.php';
    $parameters = array(
                'api_key' => self::$api_key_, 
                'api_secret' => self::$api_secret_, 
                'jobs' => 'face_crawl_['.$fb_id.']',
                'name_space' => self::$name_space_,
                'user_id' => self::$user_id_,
                'access_token' => $access_token,
                'fb_id' => $friend_id
                );
    $http_client = new HttpClient('rekognition.com');
    $http_client->setDebug(false);
    $http_client->setTimeOut(100000);
    $response = $http_client->get("/func/api/", $parameters);
    $result = $http_client->getContent();
 
    if($return_mode == 11) {
      return json_decode($result);
    }
    else {
      return $result;
    }
  }
  
  /**
   * Request to Rekognition server for face recognition task and return results encoded using JSON or parsed object
   * @param string $req: Image data
   * @param string $request_mode: Data type of $req, could be path(Rekognition_API::REQUEST_DIR), url(Rekognition_API::REQUEST_URL) or raw data(Rekognition_API::REQUEST_RAW)
   * @param string $return_mode: JSON string(Rekognition_API::RETURN_JSON), array(Rekognition_API::RETURN_ARRAY)
   * @return image metadata defined in $return_mode
   */
  public function RkFaceRecognize($req, 
                              $scale,
                              $request_mode = Rekognition_API::REQUEST_UNDEFINED, 
                              $return_mode = Rekognition_API::RETURN_JSON){
    if(!$request_mode) {
      return 'Undefined request mode!';
    }
    $result = '';
    if($request_mode == 1){
      require_once $GLOBALS['REKOGNITION_ROOT'].'HttpClient.class.php';
      $parameters = array(
                  'api_key' => self::$api_key_, 
                  'api_secret' => self::$api_secret_, 
                  'jobs' => 'face_recognize',
                  'urls' => $req,
                  'name_space' => self::$name_space_,
                  'user_id' => self::$user_id_);
      $face_detection = new HttpClient('rekognition.com');
      $face_detection->setDebug(false);
      $response = $face_detection->get("/func/api/", $parameters);
      $result = $face_detection->getContent();
    }
    else {
      $ch = curl_init();
      $im = $this->RkImageResize($req, $scale, $request_mode);  
      $data = array(
              'api_key' => self::$api_key_, 
              'api_secret' => self::$api_secret_, 
              'jobs' => 'face_recognize',
              'base64' => base64_encode($im),
              'name_space' => self::$name_space_,
              'user_id' => self::$user_id_);    
      curl_setopt($ch, CURLOPT_URL, 'http://rekognition.com/func/api/');
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $result = curl_exec($ch);
      curl_close($ch);
    }
    $this->parsed_obj_ = new Rekognition_Parser($result);

    if($return_mode == 10) {
      return $result;
    }
    elseif($return_mode == 11) {
      return json_decode($result);
    }
    else {
      return $this->parsed_obj_;
    }
  }
  
  /**
   * Request to Rekognition server for face visualize task and return results encoded using JSON or parsed object
   * @param array  $name_list: Name list for face visualization
   * @param string $return_mode: JSON string(Rekognition_API::RETURN_JSON), array(Rekognition_API::RETURN_ARRAY)
   * @return callback information defined in $return_mode
   */
  public function RkFaceVisualize($name_list,
                              $return_mode = Rekognition_API::RETURN_JSON) {
    require_once $GLOBALS['REKOGNITION_ROOT'].'HttpClient.class.php';
    $jobs = 'face_visualize[';
    if(count($name_list) == 0) {
      return "";
    }
    for($i = 0; $i < count($name_list); $i++) {
      $jobs .= $name_list[$i];
      if ($i != count($name_list) - 1) {
        $jobs .= ';';
      }
    }
    $jobs .= ']';
    $parameters = array(
                'api_key' => self::$api_key_, 
                'api_secret' => self::$api_secret_, 
                'jobs' => $jobs,
                'name_space' => self::$name_space_,
                'user_id' => self::$user_id_
                );
    $http_client = new HttpClient('rekognition.com');
    $http_client->setDebug(false);
    $response = $http_client->get("/func/api/", $parameters);
    $result = $http_client->getContent();
 
    if($return_mode == 11) {
      return json_decode($result);
    }
    else {
      return $result;
    }
  }
  
  /**
   * Request to Rekognition server for face search task and return results encoded using JSON or parsed object
   * @param string $req: Image data
   * @param string $request_mode: Data type of $req, could be path(Rekognition_API::REQUEST_DIR), url(Rekognition_API::REQUEST_URL) or raw data(Rekognition_API::REQUEST_RAW)
   * @param string $return_mode: JSON string(Rekognition_API::RETURN_JSON), array(Rekognition_API::RETURN_ARRAY)
   * @return callback information defined in $return_mode
   */
  public function RkFaceSearch($req, 
                               $scale,
                              $request_mode = Rekognition_API::REQUEST_UNDEFINED, 
                              $return_mode = Rekognition_API::RETURN_JSON){
    if(!$request_mode) {
      return 'Undefined request mode!';
    }
    $result = '';
    if($request_mode == 1){
      require_once $GLOBALS['REKOGNITION_ROOT'].'HttpClient.class.php';
      $parameters = array(
                  'api_key' => self::$api_key_, 
                  'api_secret' => self::$api_secret_, 
                  'jobs' => 'face_search',
                  'urls' => $req,
                  'name_space' => self::$name_space_,
                  'user_id' => self::$user_id_);
      $face_detection = new HttpClient('rekognition.com');
      $face_detection->setDebug(false);
      $response = $face_detection->get("/func/api/", $parameters);
      $result = $face_detection->getContent();
    }
    else {
      $ch = curl_init();
      $im = $this->RkImageResize($req, $scale, $request_mode);  
      $data = array(
              'api_key' => self::$api_key_, 
              'api_secret' => self::$api_secret_, 
              'jobs' => 'face_search',
              'base64' => base64_encode($im),
              'name_space' => self::$name_space_,
              'user_id' => self::$user_id_);  
              
      curl_setopt($ch, CURLOPT_URL, 'http://rekognition.com/func/api/');
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $result = curl_exec($ch);
      curl_close($ch);
    }
 
    if($return_mode == 11) {
      return json_decode($result);
    }
    else {
      return $result;
    }
  }
  
  /**
   * Request to Rekognition server for face delete task and return results encoded using JSON or parsed object
   * @param string $name: Name tag for deleting
   * @param array  $id_list: The list of image ids for deleting
   * @param string $return_mode: JSON string(Rekognition_API::RETURN_JSON), array(Rekognition_API::RETURN_ARRAY)
   * @return callback information defined in $return_mode
   */
  public function RkFaceDelete($name, 
                              $id_list,
                              $return_mode = Rekognition_API::RETURN_JSON) {
    require_once $GLOBALS['REKOGNITION_ROOT'].'HttpClient.class.php';
    $jobs = 'face_visualize['.$name.']{';
    if(count($id_list) == 0) {
      return "";
    }
    for($i = 0; $i < count($id_list); $i++) {
      $jobs .= $id_list[$i];
      if ($i != count($id_list) - 1) {
        $jobs .= ';';
      }
    }
    $jobs .= '}';
    $parameters = array(
                'api_key' => self::$api_key_, 
                'api_secret' => self::$api_secret_, 
                'jobs' => $jobs,
                'name_space' => self::$name_space_,
                'user_id' => self::$user_id_
                );
    $http_client = new HttpClient('rekognition.com');
    $http_client->setDebug(false);
    $response = $http_client->get("/func/api/", $parameters);
    $result = $http_client->getContent();
 
    if($return_mode == 11) {
      return json_decode($result);
    }
    else {
      return $result;
    }
  }
  
  /**
   * Request to Rekognition server for face rename task and return results encoded using JSON or parsed object
   * @param string $tag: Name tag for changing
   * @param array  $new_tag: The list of image ids for deleting
   * @param string $return_mode: JSON string(Rekognition_API::RETURN_JSON), array(Rekognition_API::RETURN_ARRAY)
   * @return callback information defined in $return_mode
   */
  public function RkFaceRename($tag, 
                              $new_tag,
                              $return_mode = Rekognition_API::RETURN_JSON) {
    $ch = curl_init();
    $data = array('api_key' => self::$api_key_, 
              'api_secret' => self::$api_secret_, 
              'jobs' => 'face_rename',
              'name_space' => self::$name_space_,
              'user_id' => self::$user_id_,
              'tag' => $tag,
              'new_tag' => $new_tag
              ); 
    curl_setopt($ch, CURLOPT_URL, 'http://rekognition.com/func/api/');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
 
    if($return_mode == 11) {
      return json_decode($result);
    }
    else {
      return $result;
    }
  }
  
  /**
   * Request to Rekognition server for face stats task and return results encoded using JSON or parsed object
   * @param string $return_mode: JSON string(Rekognition_API::RETURN_JSON), array(Rekognition_API::RETURN_ARRAY)
   * @return callback information defined in $return_mode
   */
  public function RkFaceStats($return_mode = Rekognition_API::RETURN_JSON) {
    $ch = curl_init();
    $data = array('api_key' => self::$api_key_, 
              'api_secret' => self::$api_secret_, 
              'jobs' => 'face_name_space_stats'
              ); 
    curl_setopt($ch, CURLOPT_URL, 'http://rekognition.com/func/api/');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
 
    if($return_mode == 11) {
      return json_decode($result);
    }
    else {
      return $result;
    }
  }
  
  /**
   * Request to Rekognition server for scene understanding task and return results encoded using JSON or parsed object
   * @param string $req: Image data
   * @param string $request_mode: Data type of $req, could be path(Rekognition_API::REQUEST_DIR), url(Rekognition_API::REQUEST_URL) or raw data(Rekognition_API::REQUEST_RAW)
   * @param string $return_mode: JSON string(Rekognition_API::RETURN_JSON), array(Rekognition_API::RETURN_ARRAY) or the object parsed by Rekognition_Parser(Rekognition_API::RETURN_PARSED)
   * @return image metadata defined in $return_mode
   */
  public function RkSceneUnderstanding($req, 
                              $scale,
                              $request_mode = Rekognition_API::REQUEST_UNDEFINED, 
                              $return_mode = Rekognition_API::RETURN_JSON){
    if(!$request_mode) {
      return 'Undefined request mode!';
    }
    $result = '';
    if($request_mode == 1){
      require_once $GLOBALS['REKOGNITION_ROOT'].'HttpClient.class.php';
      $parameters = array(
                  'api_key' => self::$api_key_, 
                  'api_secret' => self::$api_secret_, 
                  'jobs' => 'scene',
                  'urls' => $req
                  );
      $face_detection = new HttpClient('rekognition.com');
      $face_detection->setDebug(false);
      $response = $face_detection->get("/func/api/", $parameters);
      $result = $face_detection->getContent();
    }
    else {
      $ch = curl_init();
      $im = $this->RkImageResize($req, $scale, $request_mode);  
      $data = array(
              'api_key' => self::$api_key_, 
              'api_secret' => self::$api_secret_, 
              'jobs' => 'scene',
              'base64' => base64_encode($im)
              ); 
           
      curl_setopt($ch, CURLOPT_URL, 'http://rekognition.com/func/api/');
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $result = curl_exec($ch);
      curl_close($ch);
    }
    $this->parsed_obj_ = new Rekognition_Parser($result);
 
    if($return_mode == 10) {
      return $result;
    }
    elseif($return_mode == 11) {
      return json_decode($result);
    }
    else {
      return $this->parsed_obj_;
    }
  }
  
  /**
   * Get api usage information
   * @return Rekognition_AccountInfo (including quota number, status (success or not) and api key)
   */
  public function GetAllUsageInfo() {
    return $this->parsed_obj_->GetAllUsageInfo();
  }
  
  /**
   * Get api usage information
   * @param string $key: key ('quota', 'status', 'api_id')
   * @param string $val: value of a kind of user info queried by key
   * @return Rekognition_AccountInfo (including quota number, status (success or not) and api id)
   */
  public function GetUsageInfo($key, &$val) {
    return $this->parsed_obj_->GetUsageInfo($key, $val);
  }
  
  /**
   * Get all attributes
   * @return string or float (including image url, etc)
   */
  public function GetAttributes() {
    return $this->parsed_obj_->GetAttributes();
  }
  
  /**
   * Get attribute by key
   * @param string $key: key (e.g. 'url')
   * @param string $value: value of a kind of user info queried by key
   * @return true if the attribute exists, otherwise return false
   */
  public function GetAttribute($key, &$value) {
    return $this->parsed_obj_->GetAttribute($key, $value);
  }
  
  /**
   * Get all faces
   * @return list<Rekognition_Face> 
   */
   
  public function GetFaces() {
    return $this->parsed_obj_->GetFaces();
  }
  
  /**
   * Get number of detected faces
   * @return number of detected faces
   */
   
  public function GetFacesNum() {
    return $this->parsed_obj_->GetFacesNum();
  }
  
  /**
   * Get attribute by key
   * @param int $i: face index
   * @param Rekognition_Face $face: face object queried by key
   * @return true if the face exists, otherwise return false
   */
   
  public function GetFace($i, &$face) {
    return $this->parsed_obj_->GetFace($i, $face);
  }
  
  /**
   * Get all scene labels
   * @return list<Rekognition_Scene> 
   */
   
  public function GetSceneLabels() {
    return $this->parsed_obj_->GetSceneLabels();
  }
  
  /**
   * Get number of detected scene labels
   * @return number of detected scene labels
   */
   
  public function GetSceneLabelsNum() {
    return $this->parsed_obj_->GetSceneLabelsNum();
  }
  
  /**
   * Get scene label by index
   * @param int $i: scene index
   * @param string $label: scene label
   * @param float $score: scene score
   * @return true if the scene exists, otherwise return false
   */
  
  public function GetSceneLabel($i, &$label, &$score) {
    return $this->parsed_obj_->GetSceneLabel($i, $label, $score);
  }
}

$rapiConfig = array();
$rapiConfig['api_key'] = $rekognition_api_key;
$rapiConfig['api_secret'] = $rekognition_api_secret;
$rapiConfig['name_space'] = $rekognition_name_space;
$rapiConfig['user_id'] = $rekognition_user_id;
$rekognition = new Rekognition_API($rapiConfig);

$orbgui = new Rekognition_GUI();
$orbgui->SetColor(255,0,0);

