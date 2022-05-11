<?php

namespace STAN\Services\Storage;

class FirebaseAdapter implements StorageAdapterInterface{

  const REFERENCE = 'Firebase';

  public function __construct() { }

  public function auth($options) {

    $this->storage = new \Google\Cloud\Storage\StorageClient($options);

  }

  public function setObjectStore($options) {

  }

  public function setContainer($container) {

    $this->bucket = $this->storage->bucket($container);

  }


  public function setUrl($options) {

    $this->url = $options['public'];
    $this->private_url = $options['private'];

  }

  public function list() {

    return $this->bucket->objects();

  }

  public function upload($options) {

    if (!empty($options['private'])) {
      $predefinedAcl = 'private';
    } else {
      $predefinedAcl = 'publicRead';
    }

    $metadata = [];
    if (!empty($options['cacheControl'])) {
      $metadata['cacheControl']  = $options['cacheControl'];
    }
    if (!empty($options['contentDisposition'])) {
      $metadata['contentDisposition']  = $options['contentDisposition'];
    }
    if (!empty($options['contentEncoding'])) {
      $metadata['contentEncoding']  = $options['contentEncoding'];
    }

    if (!empty($options['path']) && is_file($options['path'])) {
      if ($options['contentEncoding']) {
        $payload = gzencode(file_get_contents($options['path']), 9);
      } else {
        $payload = fopen($options['path'], 'r');
      }
    } else {
      if (!empty($options['contentEncoding'])) {
        $payload = gzencode($options['path'], 9);
      } else {
        $payload = $options['content'];
      }
    }

    $this->bucket->upload(
        $payload,
        [
          'name'          => $options['name'],
          'predefinedAcl' => $predefinedAcl,
          'metadata'      => $metadata
        ]
    );

  }


  public function delete($options) {

    $object = $this->bucket->object($options['path']);

    if ($object->exists()) {
      $object->delete();
    }

  }

  public function info($options) {

    $object = $this->bucket->object($options['path']);

    if ($object->exists()) {
      return $object->info();
    }

  }

  public function get($options) {

    $object = $this->bucket->object($options['path']);

    if ($object->exists()) {
      return $object->downloadAsString();
    }

  }

  public function update($options) {

    $object = $this->bucket->object($options['path']);

    $data = [];
    if (!empty($options['private'])) {
      $predefinedAcl = 'private';
    } else {
      $predefinedAcl = 'publicRead';
    }
    $data['predefinedAcl'] = $predefinedAcl;

    $metadata = [];
    if ($options['contentDisposition']) {
      $metadata['contentDisposition']  = $options['contentDisposition'];
    }
    if (!empty($options['contentEncoding'])) {
      $metadata['contentEncoding']  = $options['contentEncoding'];
    }

    if ($object->exists()) {
      if ($options['newPath'] && $options['newPath'] != $options['path']) {
        $object = $object->rename($options['newPath']);
      }
      $object->update($metadata, $data);
    }

  }

  public function exists($options) {

    $object = $this->bucket->object($options['path']);

    return $object->exists();

  }

  public function download($options) {

    header("Location: " . $this->url . '/' .$options['path']);
    exit;

  }

}
