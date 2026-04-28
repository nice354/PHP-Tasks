<?php
// user_detail.php - детальная информация о пользователе
require_once 'config.php';

// Получаем ID пользователя из URL
$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($user_id <= 0) {
    header('Location: users.php');
    exit();
}

// Получаем подключение к БД
$conn = getConnection();

// Выполняем запрос для получения данных пользователя
$sql = "SELECT id, username, email, age, created_at FROM users WHERE id = $1";
$result = pg_query_params($conn, $sql, array($user_id));

// Проверяем, найден ли пользователь
if ($result && pg_num_rows($result) > 0) {
    $user = pg_fetch_assoc($result);
} else {
    header('Location: users.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Информация о пользователе</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>👤 Информация о пользователе</h1>
        
        <div class="menu">
            <a href="index.php" class="btn">Главная</a>
            <a href="users.php" class="btn">Список пользователей</a>
        </div>
        
        <div class="user-detail">
            <table class="info-table">
                <tr>
                    <td><strong>ID:</strong></td>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                </tr>
                <tr>
                    <td><strong>Имя пользователя:</strong></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                </tr>
                <tr>
                    <td><strong>Email:</strong></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                </tr>
                <tr>
                    <td><strong>Возраст:</strong></td>
                    <td><?php echo htmlspecialchars($user['age']); ?> лет</td>
                </tr>
                <tr>
                    <td><strong>Дата регистрации:</strong></td>
                    <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                </tr>
            </table>
        </div>
        
        <div class="actions">
            <a href="users.php" class="btn">← Вернуться к списку</a>
            <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-edit">✏️ Редактировать</a>
            <a href="delete_user.php?id=<?php echo $user['id']; ?>" 
               class="btn btn-delete" 
               onclick="return confirm('Вы уверены, что хотите удалить этого пользователя?')">🗑️ Удалить</a>
        </div>
    </div>
    
    <?php closeConnection($conn); ?>
</body>
</html>
