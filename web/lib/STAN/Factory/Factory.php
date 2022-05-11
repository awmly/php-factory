<?php

/**
* STAN Class
*
* Handle class registry
*
* @author Andrew Womersley
* @package STAN 4.1
*/

namespace STAN\Factory;

class Factory {

  /*
  * Factory
  * Builds a new instance of class or returns a pre existing instance
  */
  
  public function build($class, $args = false) {

    $class = '\STAN\Factory\\' . $class;

    if (class_exists($class))
    {
      return $class::build($args);
    }
    else
    {
      throw new \Exception('Class not found: ' . $class);
    }

  }

}
