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

class OpenStack {

  public static function build($args) {

    $openstack = new \STAN\Services\Storage\OpenStackAdapter();

    $openstack->auth([
      'authUrl'     => $_ENV['OPENSTACK_AUTHURL'],
      'region'      => $_ENV['OPENSTACK_REGION'],
      'username'    => $_ENV['OPENSTACK_USERNAME'],
      'password'    => $_ENV['OPENSTACK_PASSWORD'],
      'tenantId'    => $_ENV['OPENSTACK_TENNANT']
    ]);

    $openstack->setObjectStore([
      'catalogName'   => 'cloudFiles',
      'catalogType'   => 'object-store'
    ]);

    $openstack->setContainer(getVar('App.vhost'));

    $openstack->setUrl($_ENV['OPENSTACK_URL']);

    return $openstack;

  }

}
