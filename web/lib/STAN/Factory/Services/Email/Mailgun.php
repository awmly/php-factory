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

class Mailgun {

  public static function build($args) {

      $mailgun = new \STAN\Services\Email\MailgunAdapter(
        new \Mailgun\Mailgun($_ENV['MAILGUN_APIKEY']),
        replaceVars($_ENV['FROM_EMAIL_DOMAIN'])
      );

      $mailgun->setFrom('no-reply@' . replaceVars($_ENV['FROM_EMAIL_DOMAIN']), getVar('Site.name'));

      $mailgun->setOption('native-send', 'true');

      return $mailgun;

  }

}
