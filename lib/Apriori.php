<?php

  require_once('Set.php');

class Apriori {
  private $confidence = null;
  private $support = null;

  private $data = null;
  private $total = 0;

  private $k = 0;
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


    // Generate the frequent Items sets.
    while(true) {
      $result = $this->frequentItemSetList($this->k);

      // If the result is empty, there is no point continuing.
      if(empty($result)) {
        break;
      }

      $this->l[$this->k] = $result;
      $this->k++;
    }

    // Generate the confidence values.

    $this->rules();
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

  private function rules() {
    $results = [];

    for($i = 1; $i < count($this->l); ++$i) {
      $l = $this->l[$i];
      for($j = 0; $j < count($l); ++$j) {
        $set = $l[$j];
        $confidence = $this->confidence($set);

        $set->meta('confidence', $confidence);
        $results[] = $set;
      }
    }
  }

  private function frequentItemSetList($i) {

    if($i === 0) {
      return $this->initialize();
    }
    $output = [];

    $l0 = $this->l[0];
    $ln = $this->l[$i-1];

    //Iterate over the outer list.
    for($i = 0; $i < count($ln); ++$i) {

      // Iterate over the inner list.
      for($j = $i+1; $j < count($l0); ++$j) {

        $setN = $ln[$i];
        $set1 = $l0[$j];

        // Validate that $ln does not contain values in $l1
        if($setN->containsAnyOf($set1)) {
          continue;
        }

        $set = new Set(
          array_merge(
            $ln[$i]->values(),
            $l0[$j]->values()
          )
        );

        // Check that this set of items does not already exist in the list.
        foreach($output as $item) {
          if($item->equals($set)) {
            continue 2;
          }
        }

        // Check the support.
        $support = $this->support($set);

        if(!$this->hasSupport($support)) {
          continue;
        }

        $set->meta('support', $support);

        $output[] = $set;
      }
    }
    return $output;
  }

  private function initialize() {
    $list = [];

    for($i = 0; $i < $this->total; ++$i) {
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

    $results = [];
    foreach($list as $key => $value) {
      $support = $value / $this->total;
      if($this->hasSupport($support)) {

        $results[] = new Set($key, ['support' => $support]);
      }
    }

    return $results;
  }

  private function hasSupport($val) {
    return $val >= $this->support;
  }

  private function hasConfidence($val) {
    return $val >= $this->confidence;
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

    return ($count/$this->total);
  }

  private function findSet($val) {

    // Calculate the list number in which sets are located.
    $num = (count($val) - 1);

    if(!isset($this->l[$num])) { return null;}

    $l = $this->l[$num];

    $count = count($l);

    for($i = 0; $i < $count; ++$i) {
      $set = $l[$i];

      if($set->equals($val)) {
        return $set;
      }
    }
  }

  private function confidence($set) {
    var_dump('--'. $set->toString());

    $values = $set->values();
    $count = count($values);

    $results = [];
    $support = $set->meta('support');

    for($i = 0; $i < $count; ++$i) {

      for($j = 1; $j < $count; ++$j) {
        $a = array_slice($values, 0, $j);
        $b = array_slice($values, $j);

        //a -> b
        $setA = $this->findSet($a);
        //$setB = $this->findSet($b);

        $confidence = $support / $setA->meta('support');

        if(!$this->hasConfidence($confidence)) {
          continue;
        }
        // Check min confidence;
        $results[implode(',', $a) . ' => ' . implode(',', $b)] = $confidence;
      }
      //rotate
      array_push($values, array_shift($values));
    }
    return $results;
  }
}