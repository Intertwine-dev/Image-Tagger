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

require_once $GLOBALS['REKOGNITION_ROOT'].'Rekognition_API.php';

/**
* Rekognition GUI.
* Rekognition API for drawing graphics in the image
* Typical usage is:
*  <code>
*   $rekognition_gui = new Rekognition_GUI();
*  </code>
*/

class Rekognition_GUI{
  const MODE_DIR = 0;
  const MODE_URL = 1;
  const MODE_RAW = 2;
  
  private $raw_image_;
  private $r_;
  private $g_;
  private $b_;
  
  public function __construct() {
    return;
  }
  
  /**
   * Set image for processing
   *
   * @param string $req: Image data
   * @param string $mode: Data type of $req, could be path(Rekognition_GUI::MODE_DIR), url(Rekognition_GUI::MODE_URL) or raw data(Rekognition_GUI::MODE_RAW)
   */
   
  public function SetImage($req, $mode = Rekognition_GUI::MODE_DIR) {
    if($mode == Rekognition_GUI::MODE_RAW) {
      $this->raw_image_ = $req;
    }
    else {
      $this->raw_image_ = file_get_contents($req);
    }
  }
  
  /**
   * Get raw image
   *
   * @return string $raw_image_: Image data
   */
   
  public function GetImage() {
    return $this->raw_image_;
  }
  
  /**
   * Set brush color
   *
   * @param int $r 
   * @param int $g
   * @param int $b
   */
  
  public function SetColor($r, $g, $b) {
    $this->r_ = $r;
    $this->g_ = $g;
    $this->b_ = $b;
  }
  
  public function RkImageResize($req, $scale) {
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
   * Draw rectangle by a given boundingbox
   *
   * @param int $x1
   * @param int $y1
   * @param int $x2
   * @param int $y2
   * @param int $strength: brush strength
   */
   
  public function DrawRectangle($x1, $y1, $x2, $y2, $scale = 1, $strength = 5) {
    $canvas = imagecreatefromstring($this->RkImageResize($this->raw_image_, $scale));
    $color = imagecolorallocate($canvas, $this->r_, $this->g_, $this->b_);
    for($j = 0; $j < $strength; $j++) {
      imagerectangle($canvas, $x1+$j, $y1+$j, $x2-$j, $y2-$j, $color);
    }  
    ob_start();
    imagejpeg($canvas);
    $this->raw_image_ = ob_get_contents();
    ob_end_clean();
    imagedestroy($canvas);
  }
  
  /**
   * Draw rectangle by a given object
   *
   * @param Rekognition_Object $obj
   * @param int $strength: brush strength
   */
  
  public function DrawObject($obj, $scale = 1, $strength = 5) {
    $this->DrawRectangle($obj->GetX(), $obj->GetY(), $obj->GetX() + $obj->GetWidth(), $obj->GetY() + $obj->GetHeight(), $scale);
  }
  
  /**
   * Draw rectangle by a given object collection
   *
   * @param list<Rekognition_Object> $objs
   * @param int $strength: brush strength
   */
   
  public function DrawObjects($objs, $scale = 1, $strength = 5) {   
    $canvas = imagecreatefromstring($this->RkImageResize($this->raw_image_, $scale));
    $color = imagecolorallocate($canvas, $this->r_, $this->g_, $this->b_);
    for($i = 0; $i < count($objs); $i++){
      for($j = 0; $j < $strength; $j++) {
        imagerectangle($canvas, $objs[$i]->GetX()+$j, $objs[$i]->GetY()+$j, $objs[$i]->GetX() + $objs[$i]->GetWidth()-$j, $objs[$i]->GetY() + $objs[$i]->GetHeight()-$j, $color);
      }  
    }
    ob_start();
    imagejpeg($canvas);
    $this->raw_image_ = ob_get_contents();
    ob_end_clean();
    imagedestroy($canvas);
  }
 
}
