<?php

require_once('./lib/Apriori.php');

// Configuration for the Apriori object.
$shortopts = "s:c:";
$longopts = [
  'supp:',
  'conf:'
];

$options = getopt($shortopts, $longopts);

// Default Values
$confidence = .5;
$support = .5;

if(isset($options['supp'])) {
  $support = $options['supp'];
}

if(isset($options['conf'])) {
  $confidence = $options['conf'];
}


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


print("SUPPORT: $support\n");
print("CONFIDENCE: $confidence\n\n");
foreach($result as $key => $list) {
  print("--------------------Input File: $key--------------------\n");
  if(!empty($list)) {
    print($list);
  } else {
    print("\nNo Results Found\n\n");
  }
}
