<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>🧙‍♂️ Маг массивов — веб-игра по PHP</title>
<style>
* {
margin: 0;
padding: 0;
box-sizing: border-box;
}

body {
font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
background: linear-gradient(135deg, #0b1a2e, #1a2f3f);
min-height: 100vh;
display: flex;
justify-content: center;
align-items: center;
padding: 20px;
}

.game-container {
max-width: 1000px;
width: 100%;
background: rgba(255, 255, 255, 0.05);
backdrop-filter: blur(10px);
border: 2px solid rgba(255, 215, 0, 0.3);
border-radius: 40px;
padding: 30px;
box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5), 0 0 30px rgba(255, 215, 0, 0.2);
position: relative;
overflow: hidden;
}

.game-container::before {
content: "";
position: absolute;
top: -50%;
left: -50%;
width: 200%;
height: 200%;
background: radial-gradient(circle, rgba(255,215,0,0.1) 0%, transparent 70%);
animation: rotate 20s linear infinite;
z-index: -1;
}

@keyframes rotate {
from { transform: rotate(0deg); }
to { transform: rotate(360deg); }
}

h1 {
font-size: 2.8rem;
text-align: center;
color: #ffd966;
text-shadow: 0 0 15px #ff9900, 2px 2px 0 #5a3e1a;
margin-bottom: 10px;
letter-spacing: 2px;
}

.subtitle {
text-align: center;
color: #aaccff;
font-size: 1.2rem;
margin-bottom: 30px;
font-style: italic;
}

.stats {
display: flex;
justify-content: space-between;
background: linear-gradient(90deg, #2a4055, #1d2f40);
padding: 20px 25px;
border-radius: 60px;
margin-bottom: 30px;
border: 1px solid #ffd966;
box-shadow: inset 0 2px 5px rgba(0,0,0,0.5);
color: white;
font-weight: bold;
font-size: 1.2rem;
}

.score {
background: #ffd966;
color: #1e2b3a;
padding: 8px 25px;
border-radius: 40px;
box-shadow: 0 0 15px #ffb700;
}

.round {
background: #5f9ea0;
padding: 8px 25px;
border-radius: 40px;
}

.cauldron {
background: #2c3e50;
border-radius: 120px 120px 60px 60px;
padding: 30px 20px 40px;
margin-bottom: 30px;
border: 4px solid #b87333;
box-shadow: inset 0 -15px 0 #1e2b3a, 0 15px 20px rgba(0,0,0,0.6);
position: relative;
}

.cauldron::after {
content: "🧪";
position: absolute;
top: -25px;
right: 30px;
font-size: 3rem;
filter: drop-shadow(0 5px 5px #ff9900);
animation: float 3s ease-in-out infinite;
}

@keyframes float {
0% { transform: translateY(0px); }
50% { transform: translateY(-10px); }
100% { transform: translateY(0px); }
}

.cauldron h3 {
color: #ffd966;
text-align: center;
font-size: 1.8rem;
margin-bottom: 20px;
text-shadow: 2px 2px 0 #5a3e1a;
}

.ingredients-list {
display: flex;
flex-wrap: wrap;
gap: 15px;
justify-content: center;
min-height: 100px;
}

.ingredient-item {
background: linear-gradient(145deg, #f0e6d2, #d4b68a);
color: #2c1810;
padding: 12px 20px;
border-radius: 50px;
font-size: 1.2rem;
font-weight: bold;
box-shadow: 0 5px 0 #8b5a2b, 0 8px 15px rgba(0,0,0,0.4);
border: 2px solid #e6c3a0;
transition: all 0.3s;
animation: popIn 0.5s;
}

@keyframes popIn {
0% { transform: scale(0); }
80% { transform: scale(1.1); }
100% { transform: scale(1); }
}

.ingredient-item:hover {
transform: translateY(-5px);
box-shadow: 0 8px 0 #8b5a2b, 0 12px 20px rgba(0,0,0,0.4);
}

.empty-cauldron {
color: #95a5a6;
font-size: 1.5rem;
text-align: center;
padding: 30px;
background: rgba(0,0,0,0.2);
border-radius: 60px;
width: 100%;
}

.task-card {
background: linear-gradient(135deg, #3a4e62, #2c3e50);
border-left: 10px solid #ffae42;
padding: 25px;
border-radius: 30px;
margin-bottom: 30px;
color: white;
box-shadow: 0 10px 0 #1a2a36;
}

.task-card h2 {
color: #ffe484;
font-size: 1.8rem;
margin-bottom: 15px;
}

.task-text {
font-size: 1.6rem;
font-weight: bold;
color: #fff;
text-shadow: 2px 2px 0 #3a4e62;
background: rgba(0,0,0,0.2);
padding: 15px;
border-radius: 60px;
text-align: center;
}

.functions-grid {
display: grid;
grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
gap: 15px;
margin: 30px 0;
}

.function-btn {
background: linear-gradient(145deg, #4a6a82, #2c3e50);
border: 2px solid #ffb347;
color: #ffd966;
padding: 18px 10px;
font-size: 1.3rem;
font-weight: bold;
border-radius: 60px;
cursor: pointer;
transition: all 0.2s;
text-shadow: 1px 1px 0 #000;
box-shadow: 0 6px 0 #1a2a36;
position: relative;
overflow: hidden;
}

.function-btn:hover:not(:disabled) {
transform: translateY(-4px);
box-shadow: 0 10px 0 #1a2a36;
background: linear-gradient(145deg, #5a7a92, #3c4e60);
border-color: #ffd966;
}

.function-btn:active:not(:disabled) {
transform: translateY(2px);
box-shadow: 0 4px 0 #1a2a36;
}

.function-btn:disabled {
opacity: 0.5;
cursor: not-allowed;
filter: grayscale(0.5);
}

.function-btn.correct {
background: linear-gradient(145deg, #2e7d32, #1b5e20);
border-color: #a5d6a7;
color: white;
animation: correctFlash 0.5s;
}

.function-btn.wrong {
background: linear-gradient(145deg, #c62828, #8b0000);
border-color: #ffab91;
color: white;
animation: wrongShake 0.5s;
}

@keyframes correctFlash {
0% { filter: brightness(1); }
50% { filter: brightness(1.5); box-shadow: 0 0 30px #4caf50; }
100% { filter: brightness(1); }
}

@keyframes wrongShake {
0% { transform: translateX(0); }
25% { transform: translateX(-10px); }
50% { transform: translateX(10px); }
75% { transform: translateX(-5px); }
100% { transform: translateX(0); }
}

.message-box {
background: rgba(0, 0, 0, 0.6);
border-radius: 60px;
padding: 15px 25px;
margin: 20px 0;
font-size: 1.3rem;
border-left: 10px solid #ffae42;
color: #fff;
text-align: center;
animation: slideIn 0.5s;
}

@keyframes slideIn {
from { opacity: 0; transform: translateY(20px); }
to { opacity: 1; transform: translateY(0); }
}

.new-game-btn {
background: linear-gradient(145deg, #ff8c42, #ff6b35);
border: none;
color: white;
font-size: 1.5rem;
font-weight: bold;
padding: 20px 40px;
border-radius: 60px;
cursor: pointer;
width: 100%;
transition: all 0.3s;
text-shadow: 2px 2px 0 #b84c1c;
box-shadow: 0 8px 0 #b84c1c;
margin-top: 20px;
letter-spacing: 2px;
}

.new-game-btn:hover {
transform: translateY(-4px);
box-shadow: 0 12px 0 #b84c1c;
background: linear-gradient(145deg, #ff9c62, #ff7b45);
}

.footer {
text-align: center;
color: #8aaac0;
margin-top: 30px;
font-size: 1rem;
}

.cheat-sheet {
background: #1e2b3a;
border-radius: 30px;
padding: 20px;
margin-top: 30px;
border: 1px solid #ffd966;
}

.cheat-sheet h3 {
color: #ffd966;
font-size: 1.5rem;
margin-bottom: 15px;
}

.cheat-grid {
display: grid;
grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
gap: 10px;
color: #aaccff;
}

.cheat-item {
padding: 8px;
border-bottom: 1px dashed #4a5c6e;
}
</style>
</head>
<body>
<div class="game-container">
<h1>🧙‍♂️ МАГ МАССИВОВ</h1>
<div class="subtitle">Изучай функции массивов, варя магическое зелье!</div>

<?php
session_start();

if (!isset($_SESSION['game'])) {
$_SESSION['game'] = [
'ingredients' => ["Паучья лапка 🕷️", "Коготь дракона 🐉", "Слеза феникса 🔥", "Мандрагора 🌱", "Золотой шиповник ✨"],
'score' => 0,
'round' => 1,
'maxRounds' => 8,
'message' => '',
'lastCorrect' => null,
'task' => null
];
}

if (isset($_POST['action'])) {
$game = &$_SESSION['game'];
$ingredients = &$game['ingredients'];

if (!isset($game['task'])) {
$tasks = [
1 => "Добавить новый ингредиент в КОНЕЦ массива",
2 => "Удалить последний ингредиент",
3 => "Добавить ингредиент в НАЧАЛО массива",
4 => "Удалить первый ингредиент",
5 => "Узнать, сколько ингредиентов в котле",
6 => "Проверить, есть ли в котле 'Мандрагора'",
7 => "Перевернуть порядок ингредиентов",
8 => "Отсортировать ингредиенты по алфавиту",
9 => "Смешать с другим зельем",
10 => "Удалить дубликаты"
];
$game['task'] = ['id' => array_rand($tasks), 'text' => $tasks[$game['taskId'] ?? 1]];
}

$taskId = $game['task']['id'];
$choice = $_POST['action'];
$correct = false;
$message = "";

switch ($taskId) {
case 1: 
if ($choice == 1) {
array_push($ingredients, "Глаз тритона 👁️");
$message = "✅ Верно! Ты добавил Глаз тритона в конец массива.";
$correct = true;
} else {
$message = "❌ Неверно. Нужно было использовать array_push() для добавления в конец.";
}
break;

case 2: 
if ($choice == 2) {
$removed = array_pop($ingredients);
$message = "✅ Верно! Ты удалил '$removed' из котла.";
$correct = true;
} else {
$message = "❌ Неверно. array_pop() удаляет последний элемент.";
}
break;

case 3: 
if ($choice == 3) {
array_unshift($ingredients, "Звездная пыль ✨");
$message = "✅ Верно! Звездная пыль добавлена в начало.";
$correct = true;
} else {
$message = "❌ Неверно. array_unshift() добавляет элементы в начало.";
}
break;

case 4: 
if ($choice == 4) {
$removed = array_shift($ingredients);
$message = "✅ Верно! '$removed' испарился из котла.";
$correct = true;
} else {
$message = "❌ Неверно. array_shift() удаляет первый элемент.";
}
break;

case 5: 
if ($choice == 5) {
$count = count($ingredients);
$message = "✅ Верно! В котле $count ингредиентов. count() всё посчитал.";
$correct = true;
} else {
$message = "❌ Неверно. count() возвращает количество элементов.";
}
break;

case 6: 
if ($choice == 6) {
$hasMandrake = in_array("Мандрагора 🌱", $ingredients);
$status = $hasMandrake ? "есть" : "нет";
$message = "✅ Верно! in_array() показал, что мандрагора $status в котле.";
$correct = true;
} else {
$message = "❌ Неверно. in_array() проверяет наличие значения в массиве.";
}
break;

case 7: 
if ($choice == 7) {
$ingredients = array_reverse($ingredients);
$message = "✅ Верно! array_reverse() перевернул порядок ингредиентов.";
$correct = true;
} else {
$message = "❌ Неверно. array_reverse() переворачивает массив.";
}
break;

case 8: 
if ($choice == 8) {
sort($ingredients);
$message = "✅ Верно! sort() расположил ингредиенты по алфавиту.";
$correct = true;
} else {
$message = "❌ Неверно. sort() сортирует массив.";
}
break;

case 9:
if ($choice == 9) {
$elixir = ["Кровь луны 🌙", "Мёд шершня 🐝"];
$ingredients = array_merge($ingredients, $elixir);
$message = "✅ Верно! array_merge() смешал твоё зелье с эликсиром луны.";
$correct = true;
} else {
$message = "❌ Неверно. array_merge() объединяет массивы.";
}
break;

case 10:
if ($choice == 10) {
$ingredients = array_unique($ingredients);
$message = "✅ Верно! array_unique() очистил зелье от дубликатов.";
$correct = true;
} else {
$message = "❌ Неверно. array_unique() удаляет повторяющиеся значения.";
}
break;
}

if ($correct) {
$game['score'] += 10;
$game['lastCorrect'] = true;
} else {
$game['lastCorrect'] = false;
}

$game['message'] = $message;
$game['round']++;

if ($game['round'] <= $game['maxRounds']) {
$tasks = [
1 => "Добавить новый ингредиент в КОНЕЦ массива",
2 => "Удалить последний ингредиент",
3 => "Добавить ингредиент в НАЧАЛО массива",
4 => "Удалить первый ингредиент",
5 => "Узнать, сколько ингредиентов в котле",
6 => "Проверить, есть ли в котле 'Мандрагора'",
7 => "Перевернуть порядок ингредиентов",
8 => "Отсортировать ингредиенты по алфавиту",
9 => "Смешать с другим зельем",
10 => "Удалить дубликаты"
];
$newTaskId = array_rand($tasks);
$game['task'] = ['id' => $newTaskId, 'text' => $tasks[$newTaskId]];
} else {
$game['task'] = null;
}

header("Location: " . $_SERVER['PHP_SELF']);
exit();
}

if (isset($_POST['new_game'])) {
session_destroy();
session_start();
header("Location: " . $_SERVER['PHP_SELF']);
exit();
}

$game = $_SESSION['game'];
$ingredients = $game['ingredients'];
$gameOver = $game['round'] > $game['maxRounds'] || empty($ingredients);
?>

<div class="stats">
<div class="round">🔮 Раунд: <?= $game['round'] ?>/<?= $game['maxRounds'] ?></div>
<div class="score">✨ Счёт: <?= $game['score'] ?></div>
</div>

<div class="cauldron">
<h3>⚗️ Котёл знаний ⚗️</h3>
<div class="ingredients-list">
<?php if (empty($ingredients)): ?>
<div class="empty-cauldron">💨 Котёл пуст... Добавь ингредиенты!</div>
<?php else: ?>
<?php foreach ($ingredients as $item): ?>
<div class="ingredient-item"><?= $item ?></div>
<?php endforeach; ?>
<?php endif; ?>
</div>
</div>

<?php if (!$gameOver && isset($game['task'])): ?>
<div class="task-card">
<h2>📋 ТВОЁ ЗАДАНИЕ:</h2>
<div class="task-text"><?= $game['task']['text'] ?></div>
</div>

<?php if (!empty($game['message'])): ?>
<div class="message-box">
<?= $game['message'] ?>
</div>
<?php endif; ?>

<form method="POST" id="gameForm">
<div class="functions-grid">
<button type="submit" name="action" value="1" class="function-btn" <?= $game['lastCorrect'] !== null ? 'disabled' : '' ?>>1. array_push()</button>
<button type="submit" name="action" value="2" class="function-btn" <?= $game['lastCorrect'] !== null ? 'disabled' : '' ?>>2. array_pop()</button>
<button type="submit" name="action" value="3" class="function-btn" <?= $game['lastCorrect'] !== null ? 'disabled' : '' ?>>3. array_unshift()</button>
<button type="submit" name="action" value="4" class="function-btn" <?= $game['lastCorrect'] !== null ? 'disabled' : '' ?>>4. array_shift()</button>
<button type="submit" name="action" value="5" class="function-btn" <?= $game['lastCorrect'] !== null ? 'disabled' : '' ?>>5. count()</button>
<button type="submit" name="action" value="6" class="function-btn" <?= $game['lastCorrect'] !== null ? 'disabled' : '' ?>>6. in_array()</button>
<button type="submit" name="action" value="7" class="function-btn" <?= $game['lastCorrect'] !== null ? 'disabled' : '' ?>>7. array_reverse()</button>
<button type="submit" name="action" value="8" class="function-btn" <?= $game['lastCorrect'] !== null ? 'disabled' : '' ?>>8. sort()</button>
<button type="submit" name="action" value="9" class="function-btn" <?= $game['lastCorrect'] !== null ? 'disabled' : '' ?>>9. array_merge()</button>
<button type="submit" name="action" value="10" class="function-btn" <?= $game['lastCorrect'] !== null ? 'disabled' : '' ?>>10. array_unique()</button>
</div>
</form>
<?php endif; ?>

<?php if ($gameOver): ?>
<div class="task-card" style="background: linear-gradient(135deg, #4a3a2a, #2c1e0e);">
<h2>🎉 ИГРА ЗАВЕРШЕНА! 🎉</h2>
<div class="task-text" style="font-size: 2rem;">
Твой счёт: <?= $game['score'] ?> / <?= $game['maxRounds'] * 10 ?>
</div>
<div style="text-align: center; margin-top: 20px; font-size: 1.5rem;">
<?php if ($game['score'] >= 70): ?>
🏆 Ты стал МАГИСТРОМ МАССИВОВ!
<?php elseif ($game['score'] >= 40): ?>
📚 Хороший результат! Ещё немного практики.
<?php else: ?>
🌱 Только начало пути! Попробуй ещё раз.
<?php endif; ?>
</div>
</div>
<?php endif; ?>

<form method="POST">
<button type="submit" name="new_game" class="new-game-btn">🔄 НОВАЯ ИГРА</button>
</form>

<div class="cheat-sheet">
<h3>📚 ШПАРГАЛКА:</h3>
<div class="cheat-grid">
<div class="cheat-item">1. array_push() → добавить в конец</div>
<div class="cheat-item">2. array_pop() → удалить с конца</div>
<div class="cheat-item">3. array_unshift() → добавить в начало</div>
<div class="cheat-item">4. array_shift() → удалить с начала</div>
<div class="cheat-item">5. count() → количество элементов</div>
<div class="cheat-item">6. in_array() → проверить наличие</div>
<div class="cheat-item">7. array_reverse() → перевернуть</div>
<div class="cheat-item">8. sort() → отсортировать</div>
<div class="cheat-item">9. array_merge() → слить массивы</div>
<div class="cheat-item">10. array_unique() → убрать дубли</div>
</div>
</div>

<div class="footer">
🧙‍♂️ Учись играючи! PHP массивы — это просто магия ✨
</div>
</div>

<script>
<?php if (isset($game['lastCorrect'])): ?>
<?php if ($game['lastCorrect']): ?>
document.querySelector('button[value="<?= $_POST['action'] ?? '' ?>"]')?.classList.add('correct');
<?php else: ?>
document.querySelector('button[value="<?= $_POST['action'] ?? '' ?>"]')?.classList.add('wrong');
<?php endif; ?>
<?php endif; ?>

setTimeout(() => {
document.querySelectorAll('.function-btn').forEach(btn => {
btn.classList.remove('correct', 'wrong');
});
}, 2000);
</script>
</body>
</html>