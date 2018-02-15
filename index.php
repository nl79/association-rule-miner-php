<?php

require_once('./lib/Apriori.php');

// Configuration for the Apriori object.
$confidence = .6;
$support = .5;

$config = [
  'confidence' => $confidence,
  'support' => $support
];

// Instantiate the object.
$miner;
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
  $miner = new Apriori($config);

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
  $result[$file] = $miner->process($data)->result()->toString();
}

foreach($result as $key => $list) {
  print("SUPPORT: $support\n");
  print("CONFIDENCE: $confidence");

  print("--------------------Input File: $key--------------------\n");
  print($list);
}
