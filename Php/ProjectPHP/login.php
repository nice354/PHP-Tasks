<?php
require_once 'functions.php';

startSecureSession();
restoreSessionFromCookie();

// If already logged in, redirect to index
if (isLoggedIn()) {
    redirect('index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
        $error = 'Ошибка безопасности. Попробуйте еще раз.';
    } elseif (empty($login) || empty($password)) {
        $error = 'Заполните все поля';
    } else {
        $user = dbSelectOne('SELECT * FROM users WHERE login = ?', [$login]);
        
        if ($user && password_verify($password, $user['password_hash'])) {
            createUserSession($login, $remember);
            
            $redirectUrl = $_SESSION['redirect_after_login'] ?? 'index.php';
            unset($_SESSION['redirect_after_login']);
            
            redirect($redirectUrl);
        } else {
            $error = 'Неверный логин или пароль';
        }
    }
}

if (isset($_GET['success'])) {
    $success = $_GET['success'];
}

if (isset($_GET['error'])) {
    $error = $_GET['error'];
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход - Везет</title>
    <link rel="shortcut icon" href="assets/img/Logo.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .auth-container {
            max-width: 500px;
            margin: 50px auto;
            background: #fff;
            padding: 40px;
            border-radius: 30px;
        }
        .auth-title {
            font-size: 28px;
            font-weight: 700;
            color: #222;
            margin-bottom: 30px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #404040;
            margin-bottom: 8px;
        }
        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            height: 48px;
            padding: 0 20px;
            font-size: 16px;
            border: 2px solid #f2f2f2;
            border-radius: 40px;
            outline: none;
            transition: border-color 0.2s;
        }
        .form-group input:focus {
            border-color: #00D0FF;
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        .checkbox-group input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        .checkbox-group label {
            font-size: 14px;
            color: #595959;
            cursor: pointer;
        }
        .submit-btn {
            width: 100%;
            height: 56px;
            background-color: #007AFF;
            color: #fff;
            border: none;
            border-radius: 40px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .submit-btn:hover {
            background-color: #0056b3;
        }
        .auth-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #595959;
        }
        .auth-link a {
            color: #007AFF;
            text-decoration: none;
            font-weight: 600;
        }
        .auth-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body style="display: block; background-color: #f2f2f2; padding: 0;">
    <div class="auth-container">
        <h1 class="auth-title">Вход в систему</h1>
        
        <?php if ($error): ?>
            <div class="admin-feedback error" style="margin-bottom: 20px;"><?= escape($error) ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="admin-feedback" style="margin-bottom: 20px;"><?= escape($success) ?></div>
        <?php endif; ?>
        
        <form method="POST" action="login.php">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            
            <div class="form-group">
                <label for="login">Логин:</label>
                <input type="text" id="login" name="login" value="<?= escape($_POST['login'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="checkbox-group">
                <input type="checkbox" id="remember" name="remember" value="1">
                <label for="remember">Запомнить меня</label>
            </div>
            
            <button type="submit" class="submit-btn">Войти</button>
        </form>
        
        <div class="auth-link">
            Нет аккаунта? <a href="register.php">Зарегистрироваться</a>
        </div>
    </div>
</body>
</html>
