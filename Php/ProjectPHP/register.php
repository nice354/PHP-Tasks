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
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token'])) {
        $error = 'Ошибка безопасности. Попробуйте еще раз.';
    } else {
        // Validate login
        $loginValidation = validateLogin($login);
        if (!$loginValidation['valid']) {
            $errors['login'] = $loginValidation['error'];
        }
        
        // Check if login exists
        if (empty($errors['login'])) {
            $existingUser = dbSelectOne('SELECT id FROM users WHERE login = ?', [$login]);
            if ($existingUser) {
                $errors['login'] = 'Этот логин уже занят';
            }
        }
        
        // Validate password
        $passwordValidation = validatePassword($password);
        if (!$passwordValidation['valid']) {
            $errors['password'] = $passwordValidation['error'];
        }
        
        // Check password confirmation
        if ($password !== $password_confirm) {
            $errors['password_confirm'] = 'Пароли не совпадают';
        }
        
        // Validate name
        $nameValidation = validateName($name);
        if (!$nameValidation['valid']) {
            $errors['name'] = $nameValidation['error'];
        }
        
        // Validate email
        $emailValidation = validateEmail($email);
        if (!$emailValidation['valid']) {
            $errors['email'] = $emailValidation['error'];
        }
        
        // If no errors, create user
        if (empty($errors)) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            
            $result = dbExecute(
                'INSERT INTO users (login, password_hash, name, email, role) VALUES (?, ?, ?, ?, ?)',
                [$login, $passwordHash, $name, $email, 'user']
            );
            
            if ($result) {
                $success = 'Регистрация успешна! Перенаправление на страницу входа...';
                header('refresh:2;url=login.php?success=' . urlencode('Регистрация успешна! Войдите в систему'));
            } else {
                $error = 'Ошибка при создании пользователя';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация - Везет</title>
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
        .form-group input {
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
        .form-group .field-error {
            display: block;
            color: #ff0051;
            font-size: 13px;
            margin-top: 5px;
            margin-left: 20px;
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
        <h1 class="auth-title">Регистрация</h1>
        
        <?php if ($error): ?>
            <div class="admin-feedback error" style="margin-bottom: 20px;"><?= escape($error) ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="admin-feedback" style="margin-bottom: 20px;"><?= escape($success) ?></div>
        <?php endif; ?>
        
        <form method="POST" action="register.php">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            
            <div class="form-group">
                <label for="login">Логин:</label>
                <input type="text" id="login" name="login" value="<?= escape($_POST['login'] ?? '') ?>" required>
                <?php if (isset($errors['login'])): ?>
                    <span class="field-error"><?= escape($errors['login']) ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" required>
                <?php if (isset($errors['password'])): ?>
                    <span class="field-error"><?= escape($errors['password']) ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="password_confirm">Подтверждение пароля:</label>
                <input type="password" id="password_confirm" name="password_confirm" required>
                <?php if (isset($errors['password_confirm'])): ?>
                    <span class="field-error"><?= escape($errors['password_confirm']) ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="name">Имя:</label>
                <input type="text" id="name" name="name" value="<?= escape($_POST['name'] ?? '') ?>" required>
                <?php if (isset($errors['name'])): ?>
                    <span class="field-error"><?= escape($errors['name']) ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= escape($_POST['email'] ?? '') ?>" required>
                <?php if (isset($errors['email'])): ?>
                    <span class="field-error"><?= escape($errors['email']) ?></span>
                <?php endif; ?>
            </div>
            
            <button type="submit" class="submit-btn">Зарегистрироваться</button>
        </form>
        
        <div class="auth-link">
            Уже есть аккаунт? <a href="login.php">Войти</a>
        </div>
    </div>
</body>
</html>
