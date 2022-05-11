<?php

/**
* STAN Class
*
* Handle class registry
*
* @author Andrew Womersley
* @package STAN 4.1
*/

namespace STAN\Factory\Services\ImageResize;
use STAN;

class Imagick {

  public static function build($args) {

      $imagick = new \STAN\Services\ImageResize\Imagick();

      return $imagick;

  }

}
