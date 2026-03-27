<?php

echo "Задача 1: От 1 до 10<br>";
for ($i = 1; $i <= 10; $i++) {
    echo $i . " ";
}
echo "<br><br>";

echo "Задача 2: Приветствия<br>";
for ($i = 1; $i <= 5; $i++) {
    echo $i . ". Привет, мир!<br>";
}
echo "<br>";

echo "Задача 3: Обратный отсчет<br>";
for ($i = 10; $i >= 1; $i--) {
    echo $i . " ";
}
echo "<br><br>";

echo "Задача 4: Четные числа<br>";
for ($i = 2; $i <= 20; $i += 2) {
    echo $i . " ";
}
echo "<br><br>";

echo "Задача 5: Таблица умножения на 3<br>";
for ($i = 1; $i <= 10; $i++) {
    echo "3 × $i = " . (3 * $i) . "<br>";
}
echo "<br>";

echo "Задача 6: Кратные числа<br>";
for ($i = 1; $i <= 100; $i++) {
    if ($i % 7 === 0) {
        echo $i . " ";
    }
}
echo "<br><br>";

echo "Задача 7: Сумма чисел<br>";
$sum = 0;
for ($i = 1; $i <= 100; $i++) {
    $sum += $i;
}
echo "Сумма чисел от 1 до 100 = $sum<br><br>";

echo "Задача 8: Факториал<br>";
$factorial = 1;
for ($i = 1; $i <= 5; $i++) {
    $factorial *= $i;
}
echo "Факториал 5 = $factorial<br><br>";

echo "Задача 9: Счетчик букв<br>";
$word = "программирование";
$count = 0;
for ($i = 0; $i < mb_strlen($word); $i++) {
    if (mb_substr($word, $i, 1) === 'а') {
        $count++;
    }
}
echo "Буква 'а' встречается $count раза<br><br>";

echo "Задача 10: Поиск максимального<br>";
$numbers = [5, 2, 9, 1, 7];
$max = $numbers[0];
foreach ($numbers as $num) {
    if ($num > $max) {
        $max = $num;
    }
}
echo "Максимальное число: $max<br><br>";

echo "Задача 11: Список студентов<br>";
$students = [
    "Анна" => 5,
    "Иван" => 4,
    "Мария" => 5,
    "Петр" => 3
];
foreach ($students as $name => $grade) {
    echo "$name: $grade<br>";
}
echo "<br>";

echo "Задача 12: Средний балл<br>";
$total = 0;
foreach ($students as $grade) {
    $total += $grade;
}
$average = $total / count($students);
echo "Средний балл: $average<br><br>";

echo "Задача 13: Поиск товаров<br>";
$products = [
    "Хлеб" => 50,
    "Молоко" => 80,
    "Телевизор" => 20000,
    "Яблоки" => 120,
    "Ноутбук" => 50000,
    "Шоколад" => 90
];
foreach ($products as $name => $price) {
    if ($price < 1000) {
        echo "$name - $price руб.<br>";
    }
}
echo "<br>";

echo "Задача 14: Обратный порядок<br>";
$colors = ["красный", "оранжевый", "желтый", "зеленый", "голубой"];
for ($i = count($colors) - 1; $i >= 0; $i--) {
    echo $colors[$i] . "<br>";
}
echo "<br>";

echo "Задача 15: Объединение массивов<br>";
$fruits = ["яблоко", "банан", "апельсин"];
$vegetables = ["морковь", "помидор", "огурец"];
$all = array_merge($fruits, $vegetables);
echo implode(", ", $all) . "<br>";
