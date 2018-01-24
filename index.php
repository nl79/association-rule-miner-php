<?php

require_once('./lib/Apriori.php');

// Configuration for the Apriori object.
$config = [
  'confidence' => .2,
  'support' => .2
];

// Instantiate the object.
$miner = new Apriori($config);
$result = [];

// Array of input files
$files = [
  './data/1.txt',
  './data/2.txt',
  './data/3.txt',
  './data/4.txt',
  './data/5.txt',
];

foreach($files as $file) {
  $fh = fopen($file,'r');
  $data = [];

  /*
   * Pre-process the input and transform it into a simple array.
   */
  while ($line = fgets($fh)) {
    // Split line on space
    $parts = explode(',', $line);

    // Remove the transaction ID value (first column)
    // trim surrounding whitespace
    // lowercase the tokens.
    $data[] = array_map(function($o) {
      return trim(strtolower($o));
    }, array_slice($parts, 1));
  }

  /*
   * Execute the Apriori algorithm.
   */
  $result[$file] = $miner->mine($data);
}

var_dump($result);

