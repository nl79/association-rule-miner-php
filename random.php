<?php
$options = [
  "paper",
  "pen",
  "yogurt",
  "beer",
  "phone",
  "candy",
  "bread",
  "potatoes",
  "water",
  "magazine"
];

$set = [];
for ( $i = 1; $i <= 20; $i++ ) {
  $str = "$i";

  // Number of items per transaction
  $items = rand(0, 9);

  // Randomize the array.
  shuffle($options);



  for($j = 0; $j <= $items; ++$j) {
    $str .= ", $options[$j]";
  }
  $str .= "\n";
  print($str);


}

?>
