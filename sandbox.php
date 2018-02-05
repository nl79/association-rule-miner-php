<?php
/**
 * Created by IntelliJ IDEA.
 * User: nazarlesiv
 * Date: 2/4/18
 * Time: 6:16 PM
 */

//$transaction = ['pen','magazine','paper'];
//
//sort($transaction);
//
//$compare = ['paper', 'pen'];
//$vals = array_values(array_intersect($transaction, $compare));
//var_dump($vals);
//var_dump($vals == $compare);

$set = ['a', 'b'];

$rotations = count($set);

for($i = 0; $i <= $rotations; ++$i) {

  for($j = 1; $j < $rotations; ++$j) {
    $a = array_slice($set, 0, $j);
    $b = array_slice($set, $j);

    //a -> b
  }

  //rotate
  array_push($set, array_shift($set));
}