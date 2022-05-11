<?php

namespace STAN\Services\Storage;

class LocalStorageAdapter implements StorageAdapterInterface {

  const REFERENCE = 'Local';

  public function setDir($dir) {

    $this->dir = $dir;

  }

  public function info($options) {

    
  }

  public function upload($options) {

    $public = $options['public'] ? 'public/' : '';

    if (!$options['deploy']) {

      copy($options['path'], $this->dir . $public . $options['name']);

    }

  }

  public function delete($options) {

    $public = $options['public'] ? 'public/' : '';

    if ($this->exists($options)) {

      return unlink($this->dir . $public . $options['path']);
    }

  }

  public function get($options) {

    $public = $options['public'] ? 'public/' : '';

    if ($this->exists($options)) {
      return file_get_contents($this->dir . $public . $options['path']);
    }

  }

  public function exists($options) {

    $public = $options['public'] ? 'public/' : '';

    return is_file($this->dir . $public . $options['path']);

  }

  public function download($options) {

    $path = $this->dir . $options['path'];
    $filename = $options['filename'];

    header("Content-Length: " . filesize($path));

    header("Content-Type: " . \STAN\Utils\Files::getContentType($path));

    header('Content-Disposition: attachment; filename="' . $filename . '"');

    // Open file
    $handle = fopen($path, 'rb');

    // Read file in 1mb chunks
    while (!feof($handle))
    {
      echo fread($handle,(1*1048576));
    }

    // Close file handler
    fclose($handle);

  }

}
