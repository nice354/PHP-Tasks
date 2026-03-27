<?php
// Задание 1
$arr = [4, 2, 5, 19, 13, 0, 10];
$sum = 0;
foreach ($arr as $val) {
    $sum += $val * $val;
}
echo "Задание 1: " . sqrt($sum);
echo "<br><br>";

// Задание 2
$sqrt = sqrt(379);
echo "Задание 2: " . round($sqrt) . ", " . round($sqrt, 1) . ", " . round($sqrt, 2);
echo "<br><br>";

// Задание 3
$arr = [4, -2, 5, 19, -130, 0, 10];
echo "Задание 3: min=" . min($arr) . ", max=" . max($arr);
echo "<br><br>";

// Задание 4
$a = 15;
$b = 7;
echo "Задание 4: |" . $a . " - " . $b . "| = " . abs($a - $b);
echo "<br><br>";

// Задание 5
$arr = [1, 2, 3, 4, 5];
echo "Задание 5: " . array_sum($arr) / count($arr);
