<?php

// Задание 1
$students = [
    ['Иван', 5, 4, 5],
    ['Мария', 4, 5, 4],
    ['Петр', 3, 4, 3]
];

foreach ($students as $student) {
    echo $student[0] . ': ' . $student[1] . ', ' . $student[2] . ', ' . $student[3];
    echo '<br>';
}

echo '<br>';

// Задание 2
$fruits = [
    ['яблоки', 100, 10],
    ['бананы', 80, 15],
    ['апельсины', 90, 8]
];

foreach ($fruits as $fruit) {
    echo $fruit[0] . ': ' . ($fruit[1] * $fruit[2]) . ' руб.';
    echo '<br>';
}

echo '<br>';

// Задание 3
$schedule = [
    'понедельник' => ['математика', 'русский', 'физкультура'],
    'вторник' => ['литература', 'история', 'английский'],
    'среда' => ['физика', 'химия', 'биология']
];

foreach ($schedule as $day => $subjects) {
    echo strtoupper($day) . ': ' . implode(', ', $subjects);
    echo '<br>';
}

echo '<br>';

// Задание 4
$users = [
    'Анна' => ['Аватар', 'Титаник', 'Интерстеллар'],
    'Иван' => ['Форсаж', 'Рокки', 'Терминатор'],
    'Мария' => ['Золушка', 'Белоснежка', 'Алладин']
];

echo implode(', ', $users['Анна']);
echo '<br>';

echo '<br>';

// Задание 5
$storage = [
    'молочные' => [
        ['молоко', 50, 10],
        ['сыр', 200, 5],
        ['йогурт', 60, 8]
    ],
    'хлебобулочные' => [
        ['хлеб', 40, 15],
        ['батон', 45, 12],
        ['булочка', 30, 20]
    ]
];

foreach ($storage as $category => $products) {
    foreach ($products as $product) {
        if ($product[1] > 50) {
            echo $product[0] . ' - ' . $product[1] . ' руб.';
            echo '<br>';
        }
    }
}

echo '<br>';

// Задание 6
$group = [
    ['Иван', [5, 4, 5, 3, 4]],
    ['Мария', [5, 5, 5, 5, 5]],
    ['Петр', [3, 4, 3, 4, 3]],
    ['Анна', [4, 4, 5, 4, 4]]
];

$groupTotal = 0;
$groupCount = 0;

foreach ($group as $student) {
    $avg = array_sum($student[1]) / count($student[1]);
    echo $student[0] . ': ' . number_format($avg, 1);
    echo '<br>';
    $groupTotal += array_sum($student[1]);
    $groupCount += count($student[1]);
}

echo 'Средний балл группы: ' . number_format($groupTotal / $groupCount, 1);
echo '<br>';

echo '<br>';

// Задание 7
$tournament = [
    ['Анна', [10, 15, 12]],
    ['Иван', [8, 14, 16]],
    ['Мария', [12, 11, 13]],
    ['Петр', [9, 10, 11]]
];

$winner = '';
$maxScore = 0;

foreach ($tournament as $player) {
    $total = array_sum($player[1]);
    if ($total > $maxScore) {
        $maxScore = $total;
        $winner = $player[0];
    }
}

echo 'Победитель: ' . $winner . ' (' . $maxScore . ' очков)';
echo '<br>';

echo '<br>';

// Задание 8
$cart = [
    ['id' => 101, 'name' => 'Футболка', 'price' => 1500, 'quantity' => 2],
    ['id' => 102, 'name' => 'Джинсы', 'price' => 3000, 'quantity' => 1],
    ['id' => 103, 'name' => 'Кроссовки', 'price' => 4000, 'quantity' => 1],
    ['id' => 104, 'name' => 'Кепка', 'price' => 800, 'quantity' => 3]
];

$total = 0;

foreach ($cart as $item) {
    $sum = $item['price'] * $item['quantity'];
    echo $item['name'] . ' x' . $item['quantity'] . ' = ' . $sum . ' руб.';
    echo '<br>';
    $total += $sum;
}

echo 'Итого: ' . $total . ' руб.';
echo '<br>';

if ($total > 5000) {
    $discount = $total * 0.1;
    echo 'Скидка: ' . $discount . ' руб.';
    echo '<br>';
    echo 'К оплате: ' . ($total - $discount) . ' руб.';
    echo '<br>';
}
