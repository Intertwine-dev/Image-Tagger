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

require_once $GLOBALS['REKOGNITION_ROOT'].'Rekognition_Geometry.php';

/**
* Rekognition Object.
* This class is usually inherited by other classes, e.g, scene, face, etc
* Typical usage is:
*  <code>
*   $rekognition_object = new Rekognition_Object($arr);
*  </code>
*/

class Rekognition_Object {
  protected $boundingbox_;
  protected $attributes_ = array();
  protected $featured_points_ = array();
  
  public function __construct($arr) {
    foreach ($arr as $key => $val) {
      if($key == 'boundingbox') {
        $this->boundingbox_ = new Rekognition_Boundingbox($val->tl->x, 
                                                          $val->tl->y, 
                                                          $val->size->width, 
                                                          $val->size->height);
      }
      else {
        if(isset($val->x) && isset($val->y)) {
          $this->featured_points_[$key] = new Rekognition_Point($val->x, $val->y);
        }
        else{
          $this->attributes_[$key] = $val;
        }
      }
    }
  }
  
  /**
   * Get x coordinate of the leftup point
   * @return int x
   */
   
  public function GetX() {
    return $this->boundingbox_->GetX();
  }
  
  /**
   * Set x coordinate of the leftup point
   * @param int $x
   */
  
  public function SetX($x) {
    $this->boundingbox_->SetX($x);
  }
  
  /**
   * Get y coordinate of the leftup point
   * @return int y
   */
  
  public function GetY() {
    return $this->boundingbox_->GetY();
  }
  
  /**
   * Set y coordinate of the leftup point
   * @param int $y
   */
   
  public function SetY($y) {
    $this->boundingbox_->SetY($y);
  }
  
  /**
   * Get width of the boundingbox
   * @return int width
   */
   
  public function GetWidth() {
    return $this->boundingbox_->GetWidth();
  }
  
  /**
   * Set width of the boundingbox
   * @param int $width
   */
  
  public function SetWidth($width) {
    $this->boundingbox_->SetWidth($width);
  }
  
  /**
   * Get height of the boundingbox
   * @return int height
   */
   
  public function GetHeight() {
    return $this->boundingbox_->GetHeight();
  }
  
  /**
   * Set height of the boundingbox
   * @param int $height
   */
  
  public function SetHeight($height) {
    $this->boundingbox_->SetHeight($height);
  }
  
  /**
   * Get all the attributes
   * @return list<pair()>
   */
   
  public function GetAttributes() {
    return $this->attributes_;
  }
  
  /**
   * Get attribute by key
   * @param string $name: name 
   * @param string $value: value of an attribute queried by name
   * @return true if the attribute exists, otherwise return false
   */
  
  public function GetAttribute($name, &$value) {
    if(isset($this->attributes_[$name])){
      $value = $this->attributes_[$name];
      return true;
    }
    return false;
  }
  
  /**
   * Get the number of feature points
   * @return int featured point number
   */
  
  public function GetFeaturedPointsNum() {
    return count($this->featured_points_);
  }
  
  /**
   * Get the list of feature points
   * @return list<Rekognition_Point>
   */
  
  public function GetFeaturedPoints() {
    return $this->featured_points_;
  }
  
  /**
   * Get a feature point by name
   * @param string $name
   * @param float $x
   * @param float $y
   * return true if the feature point exists, otherwise return false
   */
  
  public function GetFeaturedPoint($name, &$x, &$y) {
    if(isset($this->featured_points_[$name])){
      $x = $this->featured_points_[$name]->GetX();
      $y = $this->featured_points_[$name]->GetY();
      return true;
    }
    return false;
  }
  
  /**
   * Get x coordinate of a feature point by name
   * @param string $name
   * @param float $x
   * return true if the feature point exists, otherwise return false
   */
  
  public function GetFeaturedPoint_X($name, &$x) {
    if(isset($this->featured_points_[$name])){
      $x = $this->featured_points_[$name]->GetX();
      return true;
    }
    return false;
  }
  
  /**
   * Get y coordinate of a feature point by name
   * @param string $name
   * @param float $y
   * return true if the feature point exists, otherwise return false
   */
  
  public function GetFeaturedPoint_Y($name, &$y) {
    if(isset($this->featured_points_[$name])){
      $y = $this->featured_points_[$name]->GetY();
      return true;
    }
    return false;
  }
}

/**
* Rekognition Face.
* The representation of face object
* Typical usage is:
*  <code>
*   $rekognition_face = new Rekognition_Face($arr);
*  </code>
*/

class Rekognition_Face extends Rekognition_Object {
  private $names_ = array();
  
  public function __construct($arr) {
    parent::__construct($arr);
    $name_str = '';
    if(parent::GetAttribute('name', $name_str)){
      $names = explode(',', $name_str);
      $counter = count($names) - 1;
      for($i = 0; $i < $counter; $i++){
        $pair = explode(':', $names[$i]);
        $this->names_[$i] = array();
        $this->names_[$i][$pair[0]] = floatval($pair[1]);
      }
    }  
  }
  
  /**
   * Get the names of potential guess
   * return list<pair()> names
   */
   
  public function GetNames() {
    return $this->names_;
  }
  
  /**
   * Get the number of names
   * return int the number of names
   */
   
  public function GetNamesNum() {
    return count($this->names_);
  }
  
  /**
   * Get a potential guess by index
   * @param int $i: guess index
   * @param string $name: name
   * @param float $value: value
   * return true if the index exists, otherwise return false
   */
   
  public function GetName($i, &$name, &$value) {
    if($i >= $this->GetNamesNum()) {
      return false;
    }
    foreach($this->names_[$i] as $name => $value);
    return true;
  }
}

/**
* Rekognition Scene.
* The representation of scene object
* Typical usage is:
*  <code>
*   $rekognition_scene = new Rekognition_Scene($arr);
*  </code>
*/

class Rekognition_Scene extends Rekognition_Object {
  private $labels_ = array();
  
  public function __construct($arr) {
    for($i = 0; $i < count($arr); $i++) {
      $this->labels_[$i][$arr[$i]->label] = $arr[$i]->score;
    }
  }
  
  /**
   * Get scene labels
   * return list<pair<string, float>> labels
   */
  
  public function GetLabels() {
    return $this->labels_;
  }
  
  /**
   * Get the number of scene labels
   * return int the number of scene labels
   */
   
  public function GetLabelsNum() {
    return count($this->labels_);
  }
  
  /**
   * Get label by index 
   * @param int $i: index
   * @param string $label: label
   * @param float $score: score
   * return true if the index exists, otherwise return false
   */
  
  public function GetLabel($i, &$label, &$score) {
    if($i < $this->GetLabelsNum()) {
      foreach($this->labels_[$i] as $label => $score);
      return true;
    }
    return false;
  }
}
