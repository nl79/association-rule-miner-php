<?php

  class Set {

    private $set = [];
    private $meta = [];

    public function __construct($values, $meta = null) {
      $this->set = $this->build($values);
      $this->meta = $meta;
    }

    public function toString() {
      return implode(',', $this->values());
    }

    public function get($val = null) {
    }

    public function values() {
      return array_keys($this->set);
    }

    public function containsAnyOf($set) {
      foreach($set->values() as $val) {

        if(array_key_exists($val, $this->set)) {

          return true;
        }
      }
      return false;
    }

    public function isSubsetOf($set) {
      foreach($this->values() as $item) {
        if(!in_array($item, $set)){
          return false;
        }
      }
      return true;
    }

    public function equals($set) {
      if($set->values() == $this->values()) {
        return true;
      }

      return false;
    }

    public function add($val) {
      if(is_scalar($val)) {
        $this->set[$val] = null;
      }

      return $this;
    }

    public function remove($val) {
      if(isset($this->set[$val])){
        // Delete the key
        unset($this->set[$val]);
      }

      return $this;
    }

    public function meta($key, $value = null) {

    }

    private function build($values) {

      if(empty($values)) {
        return [];
      }

      if(is_scalar($values)) {
        return [$values => null];
      }

      if(!is_array($values)) {
        return [];
      }

      $set = [];

      $sorted = sort($values);

      foreach($values as $value) {
        $set[$value] = null;
      }

      return $set;
    }
  }