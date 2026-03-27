<?php
// Задание 1
$bill = 4500;
$people = 4;
$tip_percent = 10;

$tip = $bill * ($tip_percent / 100);
$total = $bill + $tip;
$per_person = round($total / $people);

echo 'РЕСТОРАН "У ПЕТРОВИЧА"<br>';
echo "Счет: $bill руб.<br>";
echo "Чаевые ($tip_percent%): $tip руб.<br>";
echo "Итого к оплате: $total руб.<br>";
echo "Гостей: $people человека<br>";
echo "Каждый платит по: $per_person руб.<br>";

echo "<br>";

// Задание 2
$letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$numbers = '0123456789';
$all_chars = $letters . $numbers;

echo "ГЕНЕРАТОР НАДЕЖНЫХ ПАРОЛЕЙ<br>";
echo "==============================<br>";

for ($i = 1; $i <= 5; $i++) {
    $password = '';
    $digit_count = 0;

    for ($j = 0; $j < 8; $j++) {
        $random_index = mt_rand(0, strlen($all_chars) - 1);
        $password .= $all_chars[$random_index];
        if (is_numeric($all_chars[$random_index])) {
            $digit_count++;
        }
    }

    // Гарантируем минимум 2 цифры
    while ($digit_count < 2) {
        $pos = mt_rand(0, 7);
        $digit = $numbers[mt_rand(0, strlen($numbers) - 1)];
        if (!is_numeric($password[$pos])) {
            $password[$pos] = $digit;
            $digit_count++;
        }
    }

    echo "Пароль $i: $password<br>";
}

echo "<br>";

// Задание 3
$amount = 100000;
$percent = 12;
$months = 12;

$monthly_rate = $percent / 100 / 12;
$payment = round($amount * ($monthly_rate * pow(1 + $monthly_rate, $months)) / (pow(1 + $monthly_rate, $months) - 1));
$total_payment = $payment * $months;
$overpayment = $total_payment - $amount;

echo "КРЕДИТНЫЙ КАЛЬКУЛЯТОР<br>";
echo "Сумма кредита: $amount руб.<br>";
echo "Процентная ставка: $percent% годовых<br>";
echo "Срок: $months месяцев<br><br>";
echo "Ежемесячный платеж: $payment руб.<br>";
echo "Общая сумма выплат: $total_payment руб.<br>";
echo "Переплата: $overpayment руб.<br>";

echo "<br>";

// Задание 4
function rollDice() {
    return rand(1, 6);
}

$player1_wins = 0;
$player2_wins = 0;
$draws = 0;
$player1_total = 0;
$player2_total = 0;
$max_score = 0;
$max_player = '';
$max_round = 0;

echo "ИГРА В КОСТИ<br>";
echo "10 раундов<br>";
echo "=============<br><br>";

for ($round = 1; $round <= 10; $round++) {
    $p1d1 = rollDice();
    $p1d2 = rollDice();
    $p2d1 = rollDice();
    $p2d2 = rollDice();

    $p1_score = $p1d1 + $p1d2;
    $p2_score = $p2d1 + $p2d2;

    $player1_total += $p1_score;
    $player2_total += $p2_score;

    if ($p1_score > $max_score) {
        $max_score = $p1_score;
        $max_player = 'Игрок 1';
        $max_round = $round;
    }
    if ($p2_score > $max_score) {
        $max_score = $p2_score;
        $max_player = 'Игрок 2';
        $max_round = $round;
    }

    if ($p1_score > $p2_score) {
        $result = 'Победил Игрок 1';
        $player1_wins++;
    } elseif ($p2_score > $p1_score) {
        $result = 'Победил Игрок 2';
        $player2_wins++;
    } else {
        $result = 'НИЧЬЯ';
        $draws++;
    }

    echo "Раунд $round: Игрок 1 [$p1d1+$p1d2=$p1_score] vs Игрок 2 [$p2d1+$p2d2=$p2_score] → $result<br>";
}

$avg1 = round($player1_total / 10, 1);
$avg2 = round($player2_total / 10, 1);

echo "<br>СТАТИСТИКА<br>";
echo "Побед: Игрок 1 - $player1_wins, Игрок 2 - $player2_wins, Ничьи - $draws<br>";
echo "Средний результат: Игрок 1 - $avg1, Игрок 2 - $avg2<br>";
echo "Максимальный бросок: $max_score ($max_player, раунд $max_round)<br>";

echo "<br>";

// Задание 5
$correct = 0;
$total = 10;

echo "ТРЕНАЖЕР ТАБЛИЦЫ УМНОЖЕНИЯ<br>";
echo "==============================<br><br>";

for ($i = 1; $i <= $total; $i++) {
    $a = rand(2, 9);
    $b = rand(2, 9);
    $correct_answer = $a * $b;
    $user_answer = rand($correct_answer - 3, $correct_answer + 3);

    echo "Пример $i: $a × $b = ? Ваш ответ: $user_answer ";

    if ($user_answer == $correct_answer) {
        echo "Правильно!<br>";
        $correct++;
    } else {
        echo "Неправильно! Правильный ответ: $correct_answer<br>";
    }
}

$percent_correct = round($correct / $total * 100);

if ($percent_correct > 90) {
    $grade = '5 (Отлично)';
} elseif ($percent_correct >= 70) {
    $grade = '4 (Хорошо)';
} elseif ($percent_correct >= 50) {
    $grade = '3 (Удовлетворительно)';
} else {
    $grade = '2 (Неудовлетворительно)';
}

echo "<br>РЕЗУЛЬТАТ<br>";
echo "Правильных ответов: $correct из $total<br>";
echo "Процент правильных: $percent_correct%<br>";
echo "Оценка: $grade<br>";
