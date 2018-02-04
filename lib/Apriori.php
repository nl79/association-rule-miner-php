<?php

  require_once('Set.php');

class Apriori {
  private $confidence = null;
  private $support = null;

  private $data = null;
  private $total = 0;
  private $max = 0;

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

    return $this->mine();
  }

  private function mine() {

    $this->l[$this->k] = $this->flatten();
    $this->k++;


//    while($this->k < $this->max) {
//      $this->l[$this->k] = $this->l($this->k);
//      $this->k++;
//    }
    $this->l(2);
  }

  private function l($i) {

    $output = [];

    $l1 = $this->l[1];
    $ln = $this->l[$i-1];

    //Iterate over the outer list.
    for($i = 0; $i < count($ln); ++$i) {

      for($j = $i+1; $j < count($l1); ++$j) {
        $set = new Set(
          array_merge(
            $ln[$i]->values(),
            $l1[$j]->values()
          )
        );
        // Validate that the set is unique in the list.

        // Check the support.

        $output[] = $set;
      }
    }

    var_dump($output);
    return $output;
  }

  private function flatten() {
    $list = [];

    for($i = 0; $i < $this->total; ++$i) {
      $transaction = $this->data[$i];
      if(is_array($transaction)) {

        // Check if the longest transaction.
        // This would be the stopping condition for the
        // algorithm.
        $max = count($transaction);
        if($max > $this->max) {
          $this->max = $max;
        }

        foreach($transaction as $item) {
          if(isset($list[$item])) {
            $list[$item]++;
          } else {
            $list[$item] = 1;
          }
        }
      }
    }

    $results = [];
    foreach($list as $key => $value) {
      if($this->checkSupport($value)) {

        $results[] = new Set($key, ['support' => $value]);

      }
    }

    return $results;
  }

  private function hasSupport($val) {
    return $this->checkSupport($this->support($val)) >= $this->support;
  }

  private function checkSupport($sup) {
    return ($sup/$this->total) >= $this->support;
  }

  private function support($val) {
    return 0;
  }

  private function confidence($set) {

  }
}