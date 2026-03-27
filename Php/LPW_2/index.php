<?php

// Программа 1: Визитная карточка
echo "<h1>Моя первая PHP-страница</h1>";
echo "<hr>";
echo "Имя: Иван<br>";
echo "Фамилия: Иванов<br>";
echo "Дата: " . date("d.m.Y") . "<br>";
echo "Время: " . date("H:i:s");

echo "<br><br>";

// Программа 2: Переменные
$user_name = "Анна";
$user_age = 25;
$is_student = true;

echo "Имя: " . $user_name . "<br>";
echo "Возраст: " . $user_age . "<br>";

if ($is_student) {
    echo "Статус: Студент";
} else {
    echo "Статус: Не студент";
}

echo "<br><br>";

// Программа 3: Типы данных
$string = "Текст";       // Строка
$integer = 100;          // Целое число
$float = 99.99;          // Число с плавающей точкой
$boolean = true;         // Логическое значение (true/false)
$array = [1, 2, 3];     // Массив
$null = null;            // Пустое значение

echo "Строка: " . $string . "<br>";
echo "Целое число: " . $integer . "<br>";
echo "Число с плавающей точкой: " . $float . "<br>";
echo "Логическое значение: " . ($boolean ? "true" : "false") . "<br>";
echo "Массив: " . implode(", ", $array) . "<br>";
echo "Пустое значение: " . var_export($null, true) . "<br>";
