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

require_once $GLOBALS['REKOGNITION_ROOT'].'Rekognition_Object.php';

/**
* Rekognition Parser.
* Typical usage is:
*  <code>
*   $info_arr = array();
*   ...                     // Put account info into array
*   $account_info = new Rekognition_AccountInfo($info_arr);
*   if($account_info->GetAttribute('quota', $val)) {
*     echo $val;
*   }
*  </code>
*/

class Rekognition_AccountInfo {
  private $attributes_ = array();
  
  public function __construct($arr) {
    foreach($arr as $key => $val) {
      $this->attributes_[$key] = $val;
    }
  }
  
  /**
   * Get api usage information
   * @param string $key: key ('quota', 'status', 'api_id')
   * @param string $val: value of a kind of user info queried by key
   * @return string value
   */
  public function GetAttribute($key, &$val) {
    if(isset($this->attributes_[$key])) {
      $val = $this->attributes_[$key];
      return true;
    }
    else {
      return false;
    }
  }
}

/**
* Parse metadata of image analysis by passing a JSON string returned by rekognition server, and save information in this class
* Typical usage is:
*  <code>
*    $response = new Rekognition_Parser($json_str);
*  </code>
*/

class Rekognition_Parser {
  private $usage_info_;
  private $attributes_ = array();
  private $faces_ = array();
  private $scenes_;
  
  public function __construct($json_str){
    $arr = json_decode($json_str);
    foreach($arr->usage as $key => $val) {
      $this->usage_info_[$key] = $val;
    }
    
    foreach($arr as $key => $val) {
      if(!is_array($val)) {
        $this->attributes_[$key] = $val;
        continue;
      }
      if($key == 'face_detection') {
        for($i = 0; $i < count($val); $i++) {
          $this->faces_[count($this->faces_)] = new Rekognition_Face($val[$i]);
        }
      }
      if($key == 'scene_understanding') {
        $this->scenes_ = new Rekognition_Scene($val);
      }
    }
  }
  
  /**
   * Get api usage information
   * @return Rekognition_AccountInfo (including quota number, status (success or not) and api key)
   */
   
  public function GetAllUsageInfo() {
    return $this->usage_info_;
  }
  
  /**
   * Get api usage information
   * @param string $key: key ('quota', 'status', 'api_id')
   * @param string $val: value of a kind of user info queried by key
   * @return Rekognition_AccountInfo (including quota number, status (success or not) and api id)
   */
   
  public function GetUsageInfo($key, &$val) {
    if(isset($this->account_info_[$key])) {
      $val = $this->account_info_[$key];
      return true;
    }
    return false;
  }
  
  /**
   * Get all attributes
   * @return string or float (including image url, etc)
   */
  
  public function GetAttributes() {
    return $this->attributes_;
  }
  
  /**
   * Get attribute by key
   * @param string $key: key (e.g. 'url')
   * @param string $value: value of a kind of user info queried by key
   * @return true if the attribute exists, otherwise return false
   */
  
  public function GetAttribute($key, &$value) {
    if(!isset($this->attributes_[$key])){
      return false;
    }
    $value = $this->attributes_[$key];
    return true;
  }
  
  /**
   * Get all faces
   * @return list<Rekognition_Face> 
   */
  
  public function GetFaces() {
    return $this->faces_;
  }
  
  /**
   * Get number of detected faces
   * @return number of detected faces
   */
   
  public function GetFacesNum() {
    return count($this->faces_);
  }
  
  /**
   * Get attribute by key
   * @param int $i: face index
   * @param Rekognition_Face $face: face object queried by key
   * @return true if the face exists, otherwise return false
   */
   
  public function GetFace($i, &$face) {
    if($i >= $this->GetFacesNum()) {
      return false;
    }
    $face = $this->faces_[$i];
    return true;
  }
  
  /**
   * Get all scene labels
   * @return list<Rekognition_Scene> 
   */
   
  public function GetSceneLabels() {
    return $this->scenes_->GetLabels();
  }
  
  /**
   * Get number of detected scene labels
   * @return number of detected scene labels
   */
  
  public function GetSceneLabelsNum() {
    return $this->scenes_->GetLabelsNum();
  }
  
  /**
   * Get scene label by index
   * @param int $i: scene index
   * @param string $label: scene label
   * @param float $score: scene score
   * @return true if the scene exists, otherwise return false
   */
  
  public function GetSceneLabel($i, &$label, &$score) {
    return $this->scenes_->GetLabel($i, $label, $score);
  }
}
