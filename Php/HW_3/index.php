<?php

$students = [
    ['name' => 'Иван',   'grades' => [5, 4, 5, 3, 4]],
    ['name' => 'Анна',   'grades' => [5, 5, 5, 5, 5]],
    ['name' => 'Пётр',   'grades' => [3, 4, 3, 4, 3]],
    ['name' => 'Мария',  'grades' => [4, 4, 5, 4, 4]],
    ['name' => 'Алексей','grades' => [2, 3, 2, 2, 3]],
];

echo "<h2>📊 Успеваемость студентов</h2>";

foreach ($students as $student) {
    echo "<h3>👤 Студент: {$student['name']}</h3>";
    echo "Оценки: ";

    foreach ($student['grades'] as $grade) {
        echo $grade . " ";
    }
    echo "<br>";
}

echo "<h2>📋 Средние баллы</h2>";

foreach ($students as $student) {
    $sum   = 0;
    $count = 0;

    foreach ($student['grades'] as $grade) {
        $sum += $grade;
        $count++;
    }

    $average = $sum / $count;
    echo "{$student['name']}: средний балл = " . round($average, 2) . "<br>";
}

echo "<h2>⚠️ Студенты с двойками</h2>";

$has_failing_students = false;

foreach ($students as $student) {
    foreach ($student['grades'] as $grade) {
        if ($grade == 2) {
            echo "✗ {$student['name']} имеет двойки!<br>";
            $has_failing_students = true;
        }
    }
}

if (!$has_failing_students) {
    echo "✅ Все студенты без двоек!";
}
