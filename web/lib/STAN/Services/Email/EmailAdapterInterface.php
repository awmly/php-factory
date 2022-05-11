<?php

namespace STAN\Services\Email;

interface EmailAdapterInterface {

  public function setText($text);

  public function setHTML($html);

  public function setFrom($email, $name = false);

  public function setReplyTo($email, $name = false);

  public function setTo($to);

  public function setSubject($subject);

  public function setHeader($header, $value);

  public function setOption($option, $value);

  public function send();

}
