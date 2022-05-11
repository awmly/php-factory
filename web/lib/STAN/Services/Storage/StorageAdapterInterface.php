<?php

namespace STAN\Services\Storage;

interface StorageAdapterInterface {

  public function upload($options);

  public function delete($options);

  public function info($options);

  public function get($options);

  public function exists($options);

  public function download($options);

}
