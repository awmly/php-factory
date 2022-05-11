<?php

/**
* Mailgun
*
* A wrapper for PHPMailer
*
* @author Andrew Womersley
* @category Module
* @package STAN
* @link //stan:4000/stan/api/modules
*/

namespace STAN\Services\Email;

use STAN;

class PHPMailerAdapter implements EmailAdapterInterface{

    public $data = [];

    public function __construct($email) {

      $this->email = $email;

    }

    public function setText($text) {



    }

    public function setHTML($html) {

      $this->email->msgHTML($html);

    }

    public function setFrom($email, $name = false) {

      $this->email->setFrom($email, html_entity_decode($name, ENT_QUOTES));

    }

    public function setReplyTo($email, $name = false) {

      $this->email->clearReplyTos();
      $this->email->AddReplyTo($email, html_entity_decode($name, ENT_QUOTES));

    }

    public function setTo($to) {

      $this->email->clearAddresses();
      $this->email->addAddress($to);

    }

    public function setSubject($subject) {

      $this->email->Subject = $subject;

    }

    public function setHeader($header, $value) {



    }

    public function setOption($option, $value) {

      $this->email->{$option} = $value;

    }

    public function send() {

      $this->email->send();

    }

    private function emailName($email, $name) {

      if ($name) {
        $value = html_entity_decode($name, ENT_QUOTES) . " <" . $email . ">";
      } else {
        $value = $email;
      }

      return $value;

    }

}
