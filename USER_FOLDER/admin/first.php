<?php

function sum($x , $y){
    $add = $x+$y;
    $sub = $x-$y;

    return [$add, $sub];
}

$final = sum(20,10);

echo "ADDITOON : " .$final[0] .'<br>';
echo "SUB: " .$final[1];



?>