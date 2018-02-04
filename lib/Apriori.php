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


    while($this->k <= $this->max) {
      $this->l[$this->k] = $this->l($this->k);
      $this->k++;
    }

    //$this->toString();
  }

  public function toString() {
    $l = $this->l;
    for($i = 0; $i < count($l); ++ $i) {

      $c = $l[$i];
      var_dump('List#: ' . $i);

      for($j = 0; $j < count($c); ++ $j) {
        $set = $c[$j];

        var_dump($set->toString());

      }
    }
  }

  private function l($i) {

    $output = [];

    $l1 = $this->l[1];
    $ln = $this->l[$i-1];

    var_dump("l = $i");
    //Iterate over the outer list.
    for($i = 0; $i < count($ln); ++$i) {

      for($j = $i+1; $j < count($l1); ++$j) {

        $setN = $ln[$i];
        $set1 = $l1[$j];
//        var_dump('$ln[$i] - ' . $setN->toString());
//        var_dump('$l1[$j] - ' . $set1->toString());
        // Validate that $ln does not contain values in $l1
        if($setN->containsAnyOf($set1)) {
          continue;
        }

        $set = new Set(
          array_merge(
            $ln[$i]->values(),
            $l1[$j]->values()
          )
        );

        // Check that this set of items does not already exist in the list.
        foreach($output as $item) {
          if($item->equals($set)) {
            var_dump('equals');
            continue 2;
          }
        }

        //var_dump("i: $i - j: $j - " . $set->toString());


        // Check the support.
        if(!$this->hasSupport($set)) {
          continue;
        }


        $output[] = $set;
      }
    }
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
    //var_dump('support', $val);

    $count = 0;
    foreach($this->data as $transaction) {
      //var_dump('trans:  ' . implode(',', $transaction));
      if($val->isSubsetOf($transaction)) {
        $count++;
      }
    }

    if($count > 1) {
      var_dump($count);
      var_dump(implode(',', $val->values()));
    }


    return $count;
  }

  private function confidence($set) {

  }
}