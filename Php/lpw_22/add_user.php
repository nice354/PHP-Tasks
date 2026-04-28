<?php
// add_user.php - обработчик добавления пользователя
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $age = $_POST['age'] ?? 0;
    
    if (!empty($username) && !empty($email) && $age > 0) {
        $conn = getConnection();
        
        // Подготовленный запрос для PostgreSQL
        $sql = "INSERT INTO users (username, email, age) VALUES ($1, $2, $3)";
        $result = pg_query_params($conn, $sql, array($username, $email, $age));
        
        if ($result) {
            header('Location: users.php?success=1');
        } else {
            header('Location: users.php?error=1');
        }
        
        closeConnection($conn);
    } else {
        header('Location: users.php?error=1');
    }
} else {
    header('Location: users.php');
}
exit();
?>
