<?php

/**
* STAN Class
*
* Handle class registry
*
* @author Andrew Womersley
* @package STAN 4.1
*/

namespace STAN\Factory\Services\Storage;
use STAN;

class Local {

  public static function build($args) {

    $local = new \STAN\Services\Storage\LocalStorageAdapter();

    $local->setDir(PATH_BASE);

    $local->url = $_ENV['PRODUCTION_PROTOCOL'] . '://'. $_ENV['PRODUCTION_DOMAIN'];

    return $local;

  }

}
