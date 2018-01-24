<?php

class Apriori
{
  private $confidence = null;
  private $support = null;

  private $data = null;

  public function __construct($config) {
    $this->configure($config);
  }

  private function configure($config) {
    if(!is_array($config)) {
      return false;
    }

    $this->support($config['support']);
    $this->confidence($config['confidence']);
    return true;
  }

  public function __destruct()
  {
    // TODO: Implement __destruct() method.
  }

  public function confidence($val = null) {
    if(is_numeric($val)) {
      $this->confidence = $val;
      return $val;
    } else {
      return $this->confidence;
    }
  }

  public function support($val = null) {
    if(is_numeric($val)) {
      $this->support = $val;
      return $val;
    } else {
      return $this->support;
    }
  }

  public function mine($input) {
    if(!is_numeric($this->confidence) || !is_numeric($this->support)){
      throw new Exception("Invalid Support or Confidence Values");
    }
    $this->data = $input;

    var_dump($input);
    return 'test';
  }

  private function iterate() {

  }
}