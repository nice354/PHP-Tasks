<?php

function sendSMS($phone, $message) {
    echo "Отправляем SMS на номер $phone:<br>";
    echo "'$message'<br>";
    echo "SMS отправлено!<br><br>";
}

function sendEmail($email, $subject, $body) {
    echo "Отправляем email на $email:<br>";
    echo "Тема: $subject<br>";
    echo "Текст: $body<br>";
    echo "Email отправлен!<br><br>";
}

function notifyUser($name, $method, $message) {
    echo "Уведомляем пользователя $name:<br>";
    if ($method == "sms") {
        $phone = "+7-999-123-45-67";
        sendSMS($phone, $message);
    } elseif ($method == "email") {
        $email = "$name@example.com";
        $subject = "Важное уведомление";
        sendEmail($email, $subject, $message);
    } else {
        echo "Неизвестный метод уведомления<br><br>";
    }
}

notifyUser("Анна", "sms", "Ваш заказ готов к выдаче");
notifyUser("Иван", "email", "Оплата прошла успешно");
notifyUser("Мария", "sms", "Завтра встреча в 10:00");

echo "<br>";

function rollDice() {
    return rand(1, 6);
}

function playDiceGame($player1, $player2) {
    echo "ИГРА В КОСТИ: $player1 против $player2<br>";
    echo "====================<br>";
    $score1 = rollDice();
    echo "$player1 бросает кубик... Выпало: $score1<br>";
    $score2 = rollDice();
    echo "$player2 бросает кубик... Выпало: $score2<br>";
    if ($score1 > $score2) {
        echo "Победитель: $player1!<br><br>";
        return $player1;
    } elseif ($score2 > $score1) {
        echo "Победитель: $player2!<br><br>";
        return $player2;
    } else {
        echo "Ничья! Бросаем еще раз...<br><br>";
        return playDiceGame($player1, $player2);
    }
}

$winner1 = playDiceGame("Анна", "Иван");
$winner2 = playDiceGame("Мария", "Петр");
$final_winner = playDiceGame($winner1, $winner2);

echo "ГРАНД-ФИНАЛ!<br>";
echo "АБСОЛЮТНЫЙ ЧЕМПИОН: $final_winner!<br>";

echo "<br>";

function checkHomework($student_name, $task_number, $code) {
    echo "Проверяем задачу #$task_number студента $student_name:<br>";
    echo "Код: $code<br>";
    if (strpos($code, '<?php') !== false) {
        $score = 5;
        echo "Отлично! Код начинается с тега PHP<br>";
    } elseif (strpos($code, 'echo') !== false) {
        $score = 4;
        echo "Хорошо, но нет тега PHP<br>";
    } else {
        $score = 2;
        echo "Плохо, нет ни тега PHP, ни вывода<br>";
    }
    echo "Оценка: $score/5<br><br>";
    return $score;
}

function calculateAverageScore($scores) {
    $total = 0;
    $count = count($scores);
    foreach ($scores as $score) {
        $total += $score;
    }
    return $total / $count;
}

echo "ЖУРНАЛ ОЦЕНОК<br>";
echo "================<br><br>";

$scores_anna = [];
$scores_anna[] = checkHomework("Анна", 1, "<?php echo 'Hello'; ?>");
$scores_anna[] = checkHomework("Анна", 2, "echo 'World'");
$scores_anna[] = checkHomework("Анна", 3, "print 'Test'");

$scores_ivan = [];
$scores_ivan[] = checkHomework("Иван", 1, "Hello World");
$scores_ivan[] = checkHomework("Иван", 2, "<?php print 'Hi'; ?>");
$scores_ivan[] = checkHomework("Иван", 3, "<?php echo 'Good!'; ?>");

$average_anna = calculateAverageScore($scores_anna);
$average_ivan = calculateAverageScore($scores_ivan);

echo "ИТОГИ:<br>";
echo "Анна: средний балл " . round($average_anna, 2) . "<br>";
echo "Иван: средний балл " . round($average_ivan, 2) . "<br>";
