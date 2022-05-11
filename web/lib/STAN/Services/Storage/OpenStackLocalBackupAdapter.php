<?php

namespace STAN\Services\Storage;

class OpenStackLocalBackupAdapter implements StorageAdapterInterface {

  const REFERENCE = 'OpenStackLocalBackup';

  public function upload($options) {

    $this->openstack->upload($options);
    $this->local->upload($options);

  }

  public function delete($path) {

    $this->local->delete($path);
    $this->openstack->delete($path);

  }

  public function get($path) {

    return $this->openstack->get($path);

  }

  public function exists($path) {

    return $this->openstack->exists($path);

  }

  public function download($options) {

    $this->openstack->download($options);

  }

}
