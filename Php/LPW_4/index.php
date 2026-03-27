<?php

$str = '12345';
$sum = $str[0] + $str[1] + $str[2] + $str[3] + $str[4];
echo $sum;
echo "<br>";

$num = (string) 12345;
$product = $num[0] * $num[1] * $num[2] * $num[3] * $num[4];
echo $product;
echo "<br>";

$str = 'abcde';
$reversed = $str[4] . $str[3] . $str[2] . $str[1] . $str[0];
echo $reversed;
echo "<br>";

$num = 3;
echo $num--;
echo "<br>";
// Ответ: 3

$num1 = 3;
$num2 = ++$num1;
echo $num1;
echo $num2;
echo "<br>";
// Ответ: $num1=4, $num2=4

$num = 47;
$num += 7;
$num -= 18;
$num *= 10;
$num /= 15;
echo $num;
echo "<br>";
