<?php
// delete_user.php - удаление пользователя
require_once 'config.php';

// Получаем ID пользователя из URL
$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($user_id <= 0) {
    header('Location: users.php');
    exit();
}

// Получаем подключение к БД
$conn = getConnection();

// Выполняем удаление
$sql = "DELETE FROM users WHERE id = $1";
$result = pg_query_params($conn, $sql, array($user_id));

if ($result) {
    header('Location: users.php?deleted=1');
} else {
    header('Location: users.php?error=2');
}

closeConnection($conn);
exit();
?>
