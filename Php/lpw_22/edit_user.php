<?php
// edit_user.php - редактирование пользователя
require_once 'config.php';

// Получаем ID пользователя из URL
$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($user_id <= 0) {
    header('Location: users.php');
    exit();
}

// Получаем подключение к БД
$conn = getConnection();

// Обработка формы редактирования
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $age = $_POST['age'] ?? 0;
    
    if (!empty($username) && !empty($email) && $age > 0) {
        $sql = "UPDATE users SET username = $1, email = $2, age = $3 WHERE id = $4";
        $result = pg_query_params($conn, $sql, array($username, $email, $age, $user_id));
        
        if ($result) {
            header('Location: user_detail.php?id=' . $user_id . '&updated=1');
        } else {
            $error = "Ошибка при обновлении данных";
        }
    } else {
        $error = "Все поля обязательны для заполнения";
    }
}

// Получаем данные пользователя
$sql = "SELECT id, username, email, age FROM users WHERE id = $1";
$result = pg_query_params($conn, $sql, array($user_id));

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
    <title>Редактирование пользователя</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>✏️ Редактирование пользователя</h1>
        
        <div class="menu">
            <a href="index.php" class="btn">Главная</a>
            <a href="users.php" class="btn">Список пользователей</a>
            <a href="user_detail.php?id=<?php echo $user_id; ?>" class="btn">← Назад</a>
        </div>
        
        <?php if (isset($error)): ?>
            <div class="error-message">❌ <?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="add-form">
            <form method="POST">
                <div class="form-group">
                    <label>Имя пользователя:</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Возраст:</label>
                    <input type="number" name="age" value="<?php echo htmlspecialchars($user['age']); ?>" min="1" max="120" required>
                </div>
                <button type="submit" class="btn-submit">💾 Сохранить изменения</button>
            </form>
        </div>
    </div>
    
    <?php closeConnection($conn); ?>
</body>
</html>
