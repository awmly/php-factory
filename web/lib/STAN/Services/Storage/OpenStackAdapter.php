<?php

namespace STAN\Services\Storage;

class OpenStackAdapter implements StorageAdapterInterface{

  const REFERENCE = 'OpenStack';

  public function __construct() { }

  public function auth($options) {

    $httpClient = new \GuzzleHttp\Client([
        'base_uri' => \OpenStack\Common\Transport\Utils::normalizeUrl($options['authUrl']),
        'handler'  => \GuzzleHttp\HandlerStack::create(),
    ]);

    $options['identityService'] = \OpenStack\Identity\v2\Service::factory($httpClient);

    $this->openstack = new \OpenStack\OpenStack($options);

  }

  public function setObjectStore($options) {

    $this->objectStore = $this->openstack->objectStoreV1($options);

  }

  public function setContainer($container) {

    $this->container = $this->objectStore->getContainer($container);

  }

  public function setUrl($url) {

    $this->url = $url;

  }

  public function list() {

    return $this->container->listObjects();

  }

  public function upload($options) {

    if ($options['path']) {
      $options['stream'] = new \GuzzleHttp\Psr7\Stream(fopen($options['path'], 'r'));
    }

    $this->container->createObject($options);

  }


  public function delete($options) {

    if ($this->exists($options)) {

      $obj = $this->container->getObject($options['path']);

      if ($obj) {
        $obj->delete();
      }

    }

  }

  public function get($options) {

    $obj = $this->container->getObject($options['path']);

    if ($obj) {
      $stream = $obj->download();
      return $stream->getContents();
    }

  }

  public function exists($options) {

    return $this->container->objectExists($options['path']);

  }

  public function download($options) {

    header("Location: " . $this->url . '/' .$options['path']);
    exit;

  }

}
