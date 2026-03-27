<?php

// Задача 1
$arr = [
    ['a', 'b', 'c'],
    ['d', 'e', 'f'],
    ['g', 'h', 'i'],
    ['j', 'k', 'l'],
];
echo $arr[3][2] . ' ' . $arr[1][1] . ' ' . $arr[2][0] . ' ' . $arr[0][0];
echo '<br>';

// Задача 2
$arr = [
    [
        [1, 2],
        [3, 4],
    ],
    [
        [5, 6],
        [7, 8],
    ],
];
echo $arr[0][0][1] . ' ' . $arr[1][0][1] . ' ' . $arr[1][1][0];
echo '<br>';

// Задача 3
$arr = [[1, 2, 3], [4, 5, 6, 7], [8, 9]];
$sum = 0;
for ($i = 0; $i < count($arr); $i++) {
    for ($j = 0; $j < count($arr[$i]); $j++) {
        $sum += $arr[$i][$j];
    }
}
echo $sum;
echo '<br>';

// Задача 4
$arr = [
    [
        [1, 2, 3],
        [6, 7, 8],
        [3, 8, 4],
        [6, 7, 9],
    ],
    [
        [9, 1, 2],
        [4, 5, 6],
    ],
    [
        [9, 1, 2],
        [4, 5, 6],
        [5, 6, 3],
    ],
];
$sum = 0;
for ($i = 0; $i < count($arr); $i++) {
    for ($j = 0; $j < count($arr[$i]); $j++) {
        for ($k = 0; $k < count($arr[$i][$j]); $k++) {
            $sum += $arr[$i][$j][$k];
        }
    }
}
echo $sum;
echo '<br>';

// Задача 5
$result = [];
for ($i = 0; $i < 3; $i++) {
    for ($j = 1; $j <= 5; $j++) {
        $result[$i][] = $j;
    }
}
print_r($result);
