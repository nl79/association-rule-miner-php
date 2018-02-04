<?php
/**
 * Created by IntelliJ IDEA.
 * User: nazarlesiv
 * Date: 2/4/18
 * Time: 6:16 PM
 */

$transaction = ['pen','magazine','paper'];

sort($transaction);

$compare = ['paper', 'pen'];

$vals = array_values(array_intersect($transaction, $compare));

var_dump($vals);

var_dump($vals == $compare);