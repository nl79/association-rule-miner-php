<?php

class Apriori
{
  private $confidence = null;
  private $support = null;

  private $data = null;
  private $total = 0;

  private $k = 1;
  private $l = [];

  private $run = true;

  public function __construct($config) {
    $this->configure($config);
  }

  private function configure($config) {
    if(!is_array($config)) {
      return false;
    }

    $this->minSupport($config['support']);
    $this->minConfidence($config['confidence']);
    return true;
  }

  public function __destruct()
  {
    // TODO: Implement __destruct() method.
  }

  public function minConfidence($val = null) {
    if(is_numeric($val)) {
      $this->confidence = $val;
      return $val;
    } else {
      return $this->confidence;
    }
  }

  public function minSupport($val = null) {
    if(is_numeric($val)) {
      $this->support = $val;
      return $val;
    } else {
      return $this->support;
    }
  }

  public function process($input) {
    if(!is_numeric($this->confidence) || !is_numeric($this->support)){
      throw new Exception("Invalid Support or Confidence Values");
    }

    if(empty(($input))) {
      return [];
    }
    $this->data = $input;
    $this->total = count($input);

    var_dump($input);

    return $this->mine();
  }

  private function mine() {

    // Build l1
    $this->l[] = array_filer($this->data, function($var) {

    });

    while($this->run === true) {
      $count = count($in);

      for($i = 0; $i < $count; ++$i) {

        $item = $in[$i]

      }
    }

  }

  private function isSupport($val) {
    
    return $this->support($val) >= $this->support;
  }

  private function support($set) {

  }

  private function confidence($set) {

  }
}