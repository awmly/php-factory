<?php

/**
* Mailgun
*
* A wrapper for mailgun
*
* @author Andrew Womersley
* @category Module
* @package STAN
* @link //stan:4000/stan/api/modules
*/

namespace STAN\Services\Email;

use STAN;

class MailgunAdapter implements EmailAdapterInterface{

    public $data = [];

    public function __construct($mailgun, $domain) {

      $this->mailgun = $mailgun;

      $this->domain = $domain;
      
    }

    public function setText($text) {

      $this->data['text'] = $text;

    }

    public function setHTML($html) {

      $this->data['html'] = $html;

    }

    public function setFrom($email, $name = false) {

      $this->data['from'] = $this->emailName($email, $name);

    }

    public function setReplyTo($email, $name = false) {

      $this->setHeader('Reply-To', $this->emailName($email, $name));

    }

    public function setTo($to) {

      $this->data['to'] = $to;

    }

    public function setSubject($subject) {

      $this->data['subject'] = $subject;

    }

    public function setHeader($header, $value) {

      $this->data['h:' . $header] = $value;

    }

    public function setOption($option, $value) {

      $this->data['o:' . $option] = $value;

    }

    public function send() {

      $this->mailgun->sendMessage($this->domain, $this->data);

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
