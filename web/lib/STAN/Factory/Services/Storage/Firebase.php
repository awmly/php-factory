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

class Firebase {

  public static function build($args = []) {

    $firebase = new \STAN\Services\Storage\FirebaseAdapter();

    $firebase->auth([
      'keyFilePath' => PATH_BASE . '/config/google-service-account.json'
    ]);

    $bucket = replaceVars($_ENV['FIREBASE_BUCKET']);
    $publicURL  = str_replace("{{bucket}}", $bucket, $_ENV['FIREBASE_PUBLIC_URL']);
    $privateURL = str_replace("{{bucket}}", $bucket, $_ENV['FIREBASE_PRIVATE_URL']);

    $firebase->setContainer($bucket);

    $firebase->setUrl([
      'public'  => $publicURL,
      'private' => $privateURL
    ]);

    return $firebase;

  }

}
