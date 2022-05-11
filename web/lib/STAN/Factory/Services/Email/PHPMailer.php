<?php

/**
* STAN Class
*
* Handle class registry
*
* @author Andrew Womersley
* @package STAN 4.1
*/

namespace STAN\Factory\Services\Email;
use STAN;

class PHPMailer {

  public static function build($args) {

      $phpmailer = new \STAN\Services\Email\PHPMailerAdapter(
        new \PHPMailer\PHPMailer\PHPMailer()
      );

      $phpmailer->setFrom('no-reply@test.com');

      $phpmailer->setOption('Encoding', 'base64');
      $phpmailer->setOption('CharSet', 'UTF-8');
      $phpmailer->email->IsHTML(true);

      return $phpmailer;

  }

}
