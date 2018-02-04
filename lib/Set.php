<?php

  class Set {

    private $set = [];
    private $meta = [];

    public function __construct($values, $meta = null) {
      $this->set = $this->build($values);
      $this->meta = $meta;
    }

    public function get($val = null) {
    }

    public function values() {
      return array_keys($this->set);
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

      foreach($values as $value) {
        $set[$value] = null;
      }

      return $set;
    }
  }