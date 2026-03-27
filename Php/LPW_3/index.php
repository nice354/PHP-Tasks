<?php
$name = "Иван Иванов";           // string
$birth_year = 1990;               // int
$hobby = "программирование";      // string
$is_student = true;               // bool
$gpa = 4.5;                       // float
$city = "Москва";                 // string

$current_year = (int) date("Y");
$age = $current_year - $birth_year;

$bg_color = "#e8f5e9";
$text_color = "#1b5e20";
$accent_color = "#388e3c";

echo "<!DOCTYPE html>
<html lang='ru'>
<head>
  <meta charset='UTF-8'>
  <title>О себе — $name</title>
  <style>
    body {
      background-color: $bg_color;
      color: $text_color;
      font-family: Arial, sans-serif;
      padding: 30px;
    }
    .container {
      max-width: 600px;
      margin: 0 auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.1);
    }
    h1 { color: $accent_color; }
    .calc-box {
      background: $bg_color;
      border-left: 4px solid $accent_color;
      padding: 15px 20px;
      margin-top: 20px;
      border-radius: 6px;
      font-family: monospace;
      font-size: 15px;
    }
    .label { color: #555; }
  </style>
</head>
<body>
  <div class='container'>
    <h1>О себе</h1>
    <p><span class='label'>Имя:</span> $name</p>
    <p><span class='label'>Город:</span> $city</p>
    <p><span class='label'>Увлечение:</span> $hobby</p>
    <p><span class='label'>Студент:</span> " . ($is_student ? "Да" : "Нет") . "</p>
    <p><span class='label'>Средний балл:</span> $gpa</p>
    <p><span class='label'>Сегодня:</span> " . date("d.m.Y") . "</p>

    <div class='calc-box'>
      <strong>Калькулятор возраста</strong><br><br>
      Год рождения: $birth_year<br>
      Текущий год: $current_year<br>
      Ваш возраст: $age лет
    </div>
  </div>
</body>
</html>";
?>
