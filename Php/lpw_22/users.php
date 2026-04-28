<?php
// users.php - вывод списка пользователей из БД PostgreSQL
require_once 'config.php';

// Проверяем сообщения об успехе/ошибке
$success = isset($_GET['success']) ? $_GET['success'] : null;
$error = isset($_GET['error']) ? $_GET['error'] : null;

// Получаем подключение к БД
$conn = getConnection();

// ============================================
// 1. ВЫПОЛНЯЕМ SQL-ЗАПРОС
// ============================================
$sql = "SELECT id, username, email, age, created_at FROM users ORDER BY id";
$result = pg_query($conn, $sql);

// ============================================
// 2. ПРОВЕРЯЕМ, ЕСТЬ ЛИ ДАННЫЕ
// ============================================
$users = [];
if ($result && pg_num_rows($result) > 0) {
    while ($row = pg_fetch_assoc($result)) {
        $users[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список пользователей</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>👥 Список пользователей</h1>
        
        <?php if ($success == 1): ?>
            <div class="success-message">✅ Пользователь успешно добавлен!</div>
        <?php endif; ?>

        <?php if ($error == 1): ?>
            <div class="error-message">❌ Ошибка при добавлении пользователя</div>
        <?php endif; ?>
        
        <div class="menu">
            <a href="index.php" class="btn">Главная</a>
            <a href="users.php" class="btn btn-active">Список пользователей</a>
        </div>
        
        <div class="info">
            <p>Всего пользователей: <strong><?php echo count($users); ?></strong></p>
        </div>
        
        <?php if (empty($users)): ?>
            <div class="warning">
                ⚠️ В базе данных пока нет пользователей.
            </div>
        <?php else: ?>
            <table class="users-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя пользователя</th>
                        <th>Email</th>
                        <th>Возраст</th>
                        <th>Дата регистрации</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo $user['age']; ?></td>
                        <td><?php echo $user['created_at']; ?></td>
                        <td>
                            <a href="user_detail.php?id=<?php echo $user['id']; ?>" class="btn-small">Подробнее</a>
                            <a href="delete_user.php?id=<?php echo $user['id']; ?>" 
                               class="btn-small btn-delete" 
                               onclick="return confirm('Вы уверены, что хотите удалить этого пользователя?')">Удалить</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        
        <!-- Форма добавления пользователя -->
        <div class="add-form">
            <h3>➕ Добавить нового пользователя</h3>
            <form method="POST" action="add_user.php">
                <div class="form-group">
                    <input type="text" name="username" placeholder="Имя пользователя" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="number" name="age" placeholder="Возраст" min="1" max="120" required>
                </div>
                <button type="submit" class="btn-submit">Добавить</button>
            </form>
        </div>
        
        <div class="code-example">
            <h3>📝 PHP-код для запроса (PostgreSQL):</h3>
            <pre><code>$sql = "SELECT id, username, email, age, created_at FROM users";
$result = pg_query($conn, $sql);

while ($row = pg_fetch_assoc($result)) {
    echo $row['username'] . " - " . $row['email'];
}</code></pre>
        </div>
    </div>
    
    <?php closeConnection($conn); ?>
</body>
</html>
