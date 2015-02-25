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

/**
* Rekognition Point.
* Point representation
* Typical usage is:
*  <code>
*   $rekognition_point = new Rekognition_Point($x, $y);
*  </code>
*/

class Rekognition_Point {
  private $x_;
  private $y_;
  
  public function __construct($x, $y) {
    $this->x_ = $x;
    $this->y_ = $y;
  }
  
  /**
   * Get x
   * @return float x
   */
   
  public function GetX() {
    return $this->x_;
  }
  
  /**
   * Set x
   * @param: float x
   */
   
  public function SetX($x) {
    $this->x_ = $x;
  }
  
  /**
   * Get y
   * @return float y
   */
   
  public function GetY() {
    return $this->y_;
  }
  
  /**
   * Set y
   * @param: float y
   */
  
  public function SetY($y) {
    $this->y_ = $y;
  }
}

/**
* Rekognition Size.
* Size representation
* Typical usage is:
*  <code>
*   $rekognition_size = new Rekognition_Size($width, $height);
*  </code>
*/

class Rekognition_Size {
  private $width_;
  private $height_;
  
  public function __construct($width, $height) {
    $this->width_ = $width;
    $this->height_ = $height;
  }
  
  /**
   * Get width
   * @return float width
   */
   
  public function GetWidth() {
    return $this->width_;
  }
  
  /**
   * Set width
   * @param: float width
   */
   
  public function SetWidth($width) {
    $this->width_ = $width;
  }
  
  /**
   * Get height
   * @return float height
   */
  
  public function GetHeight() {
    return $this->height_;
  }
  
  /**
   * Set height
   * @param float height
   */
   
  public function SetHeight($height) {
    $this->height_ = $height;
  }
}

/**
* Rekognition Size.
* Size representation
* Typical usage is:
*  <code>
*   $rekognition_Boundingbox = new Rekognition_Boundingbox($x, $y, $width, $height);
*  </code>
*/

class Rekognition_Boundingbox {
  private $upleft_;
  private $size_;
  
  public function __construct($x, $y, $width, $height) {
    $this->upleft_ = new Rekognition_Point($x, $y);
    $this->size_ = new Rekognition_Size($width, $height);
  }
  
  /**
   * Get x
   * @return float x
   */
  
  public function GetX() {
    return $this->upleft_->GetX();
  }
  
  /**
   * Set x
   * @param float x
   */
  
  public function SetX($x) {
    $this->upleft_->SetX($x);
  }
  
  /**
   * Get y
   * @return float y
   */
  
  public function GetY() {
    return $this->upleft_->GetY();
  }
  
  /**
   * Set y
   * @param float y
   */
   
  public function SetY($y) {
    $this->upleft_->SetY($y);
  }
  
  /**
   * Get width
   * @return float width
   */
  
  public function GetWidth() {
    return $this->size_->GetWidth();
  }
  
  /**
   * Set width
   * @param float width
   */
   
  public function SetWidth($width) {
    $this->size_->SetWidth($width);
  }
  
  /**
   * Get height
   * @return float height
   */
   
  public function GetHeight() {
    return $this->size_->GetHeight();
  }
  
  /**
   * Set height
   * @param float height
   */
  
  public function SetHeight($height) {
    $this->size_->SetHeight($height);
  }
}

