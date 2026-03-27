<?php

// Задачность 1
function getDivisors($n) {
    $divisors = [];
    for ($i = 1; $i <= $n; $i++) {
        if ($n % $i === 0) $divisors[] = $i;
    }
    return $divisors;
}

// Задачность 2
function getCommonDivisors($a, $b) {
    return array_intersect(getDivisors($a), getDivisors($b));
}

// Задачность 3
function digitSum($n) {
    $sum = 0;
    foreach (str_split((string)abs($n)) as $digit) $sum += (int)$digit;
    return $sum;
}

// Задачность 4
function getCurrentDayOfWeek() {
    $days = ['Воскресенье','Понедельник','Вторник','Среда','Четверг','Пятница','Суббота'];
    return $days[(int)date('w')];
}

// Задачность 5
function getDayOfWeekByDate($date) {
    $days = ['Воскресенье','Понедельник','Вторник','Среда','Четверг','Пятница','Суббота'];
    return $days[(int)date('w', strtotime($date))];
}

// Задачность 6
function secondsToDays($seconds) {
    return $seconds / 86400;
}

// Задачность 7
function isLeapYear($year) {
    return ($year % 4 === 0 && $year % 100 !== 0) || ($year % 400 === 0);
}

// Задачность 8
function isPrime($n) {
    if ($n < 2) return false;
    for ($i = 2; $i <= sqrt($n); $i++) {
        if ($n % $i === 0) return false;
    }
    return true;
}

echo '№1 Делители числа 12: ' . implode(', ', getDivisors(12));
echo '<br>';

echo '№2 Общие делители 12 и 18: ' . implode(', ', getCommonDivisors(12, 18));
echo '<br>';

echo '№3 Сумма цифр числа 12345: ' . digitSum(12345);
echo '<br>';

echo '№4 Текущий день недели: ' . getCurrentDayOfWeek();
echo '<br>';

echo '№5 День недели для 2025-01-01: ' . getDayOfWeekByDate('2025-01-01');
echo '<br>';

echo '№6 86400 секунд = ' . secondsToDays(86400) . ' сут.';
echo '<br>';

echo '№7 2024 год високосный: ' . (isLeapYear(2024) ? 'Да' : 'Нет');
echo '<br>';

echo '№8 17 простое число: ' . (isPrime(17) ? 'Да' : 'Нет');
