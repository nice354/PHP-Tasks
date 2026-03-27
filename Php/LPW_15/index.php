<?php
// Задание 1.1
$name = "  Иван  ";
$name = trim($name);
$name = ucfirst($name);
echo "Привет, $name!";
echo "<br><br>";

// Задание 1.2
$password = "qwerty123";
if (strlen($password) >= 8) {
    echo "Пароль надежный";
} else {
    echo "Пароль слишком короткий";
}
echo "<br><br>";

// Задание 1.3
$email = "student@university.edu";
$pos = strpos($email, "@");
echo substr($email, 0, $pos);
echo "<br><br>";

// Задание 2.1
$text = "Это плохой пример текста, плохой плохой!";
echo str_replace("плохой", "хороший", $text);
echo "<br><br>";

// Задание 2.2
$sentence = "быстро коричневая лиса прыгает";
echo ucwords($sentence);
echo "<br><br>";

// Задание 2.3
$words = "один два три четыре";
$arr = explode(" ", $words);
$arr = array_reverse($arr);
echo implode(" ", $arr);
echo "<br><br>";

// Задание 3.1
$card = "1234 5678 9012 3456";
$last4 = substr($card, -4);
$masked = str_repeat("*", strlen($card) - 4) . $last4;
// Восстановим пробелы
$result = substr($masked, 0, 4) . " " . substr($masked, 4, 4) . " " . substr($masked, 8, 4) . " " . $last4;
echo $result;
echo "<br><br>";

// Задание 3.2
$article = "PHP (рекурсивный акроним словосочетания PHP: Hypertext Preprocessor) - распространённый язык программирования...";
$len = strlen($article);
$wordCount = str_word_count($article);
echo "Длина текста: $len символов, количество слов: $wordCount";
echo "<br><br>";

// Задание 3.3
$url = "https:// example.com/портал/index.html";
echo str_replace(" ", "%20", $url);
echo "<br><br>";

// Задание 4.1
$fullName = "Иванов Иван Иванович";
$parts = explode(" ", $fullName);
$lastName = $parts[0];
$firstInitial = mb_substr($parts[1], 0, 1);
$middleInitial = mb_substr($parts[2], 0, 1);
echo "$lastName $firstInitial. $middleInitial.";
