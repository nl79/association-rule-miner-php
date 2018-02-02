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

    // Build C1
    $count = count($this->data);
    $list = [];

    for($i = 0; $i < $count; ++$i) {
      $transaction = $this->data[$i];
      if(is_array($transaction)) {
        foreach($transaction as $item) {
          if(isset($list[$item])) {
            $list[$item]++;
          } else {
            $list[$item] = 1;
          }
        }
      }
    }

    // Filter out the items with low support
    // Store the items in l[0]
    $this->l[] = array_filter($list, function($val) {
      return $this->checkSupport($val);
    });

    var_dump($this->l);

  }

  private function hasSupport($val) {
    return $this->checkSupport($this->support($val)) >= $this->support;
  }

  private function checkSupport($sup) {
    //var_dump($sup);
    return ($sup/$this->total) >= $this->support;
  }

  private function support($val) {
    return 0;
  }

  private function confidence($set) {

  }
}