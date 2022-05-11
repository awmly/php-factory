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

class GdLib {

  public static function build($args) {

      $gdlib = new \STAN\Services\ImageResize\GdLib();

      return $gdlib;

  }

}
