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

class OpenStackLocalBackup {

  public static function build($args) {

    $openstackLocal = new \STAN\Services\Storage\OpenStackLocalBackupAdapter();

    $openstackLocal->local = Local::build($args);
    $openstackLocal->openstack = OpenStack::build($args);

    return $openstackLocal;

  }

}
