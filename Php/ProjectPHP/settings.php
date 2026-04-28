<?php
require_once 'config.php';
require_once 'db.php';
require_once 'functions.php';

// Запуск сессии и восстановление из cookie
startSecureSession();
restoreSessionFromCookie();

// Проверка авторизации
requireAuth();

// Получение данных пользователя
$user = getCurrentUser();

$error = '';
$success = '';

// Обработка изменения имени
if (isset($_POST['action']) && $_POST['action'] === 'update_name') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = 'Неверный CSRF токен';
    } else {
        $name = trim($_POST['name'] ?? '');
        $validationError = validateName($name);
        
        if ($validationError) {
            $error = $validationError;
        } else {
            $result = dbExecute(
                'UPDATE users SET name = $1 WHERE id = $2',
                [$name, $user['id']]
            );
            
            if ($result) {
                $_SESSION['user_name'] = $name;
                $user['name'] = $name;
                $success = 'Имя успешно изменено';
            } else {
                $error = 'Ошибка при изменении имени';
            }
        }
    }
}

// Обработка изменения email
if (isset($_POST['action']) && $_POST['action'] === 'update_email') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = 'Неверный CSRF токен';
    } else {
        $email = trim($_POST['email'] ?? '');
        $validationError = validateEmail($email);
        
        if ($validationError) {
            $error = $validationError;
        } else {
            $result = dbExecute(
                'UPDATE users SET email = $1 WHERE id = $2',
                [$email, $user['id']]
            );
            
            if ($result) {
                $user['email'] = $email;
                $success = 'Email успешно изменен';
            } else {
                $error = 'Ошибка при изменении email';
            }
        }
    }
}

// Обработка смены пароля
if (isset($_POST['action']) && $_POST['action'] === 'change_password') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = 'Неверный CSRF токен';
    } else {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $newPasswordConfirm = $_POST['new_password_confirm'] ?? '';
        
        $userData = dbSelectOne(
            'SELECT password_hash FROM users WHERE id = $1',
            [$user['id']]
        );
        
        if (!$userData) {
            $error = 'Пользователь не найден';
        } elseif (!password_verify($currentPassword, $userData['password_hash'])) {
            $error = 'Неверный текущий пароль';
        } else {
            $validationError = validatePassword($newPassword);
            
            if ($validationError) {
                $error = $validationError;
            } elseif ($newPassword !== $newPasswordConfirm) {
                $error = 'Новые пароли не совпадают';
            } else {
                $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                
                $result = dbExecute(
                    'UPDATE users SET password_hash = $1 WHERE id = $2',
                    [$newPasswordHash, $user['id']]
                );
                
                if ($result) {
                    $success = 'Пароль успешно изменен';
                } else {
                    $error = 'Ошибка при изменении пароля';
                }
            }
        }
    }
}

// Получение корзины для отображения
$cart = getCart();
$cartTotal = getCartTotal();
$cartCount = getCartCount();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Настройки профиля - Везет</title>
    <link rel="shortcut icon" href="assets/img/Logo.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Дополнительные стили для страницы настроек */
        .settings-form {
            background: #fff;
            padding: 24px;
            border-radius: 18px;
            margin-bottom: 16px;
        }
        
        .settings-form h2 {
            font-size: 18px;
            font-weight: 700;
            color: #222;
            margin-bottom: 18px;
        }
        
        .form-group {
            margin-bottom: 16px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: #404040;
        }
        
        .form-group input {
            width: 100%;
            height: 48px;
            padding: 0 18px;
            border: none;
            border-radius: 30px;
            font-size: 15px;
            background-color: #f2f2f2;
            color: #404040;
            outline: none;
            font-weight: 500;
        }
        
        .form-group input:focus {
            background-color: #e8e8e8;
        }
        
        .btn-save {
            background-color: #00D0FF;
            color: #fff;
            border-radius: 30px;
            padding: 14px 32px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: background 0.2s;
        }
        
        .btn-save:hover {
            background-color: #00b8e0;
        }
        
        .feedback-message {
            padding: 14px 20px;
            border-radius: 14px;
            margin-bottom: 16px;
            font-size: 14px;
            font-weight: 500;
        }
        
        .feedback-message.success {
            background: #d4edda;
            color: #155724;
        }
        
        .feedback-message.error {
            background: #f8d7da;
            color: #721c24;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #007AFF;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 16px;
        }
        
        .back-link:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    
    <?php include 'partials/header.php'; ?>
    
    <?php include 'partials/aside.php'; ?>
    
    <main>
        <a href="index.php" class="back-link">← Вернуться на главную</a>
        
        <h1 class="main-title">Настройки профиля</h1>
        
        <?php if ($success): ?>
            <div class="feedback-message success"><?= escape($success) ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="feedback-message error"><?= escape($error) ?></div>
        <?php endif; ?>
        
        <!-- Форма изменения имени -->
        <div class="settings-form">
            <h2>Изменить имя</h2>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <input type="hidden" name="action" value="update_name">
                
                <div class="form-group">
                    <label for="name">Новое имя</label>
                    <input type="text" id="name" name="name" required 
                           value="<?= escape($user['name']) ?>"
                           placeholder="Введите ваше имя">
                </div>
                
                <button type="submit" class="btn-save">Сохранить имя</button>
            </form>
        </div>
        
        <!-- Форма изменения email -->
        <div class="settings-form">
            <h2>Изменить email</h2>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <input type="hidden" name="action" value="update_email">
                
                <div class="form-group">
                    <label for="email">Новый email</label>
                    <input type="email" id="email" name="email" required 
                           value="<?= escape($user['email']) ?>"
                           placeholder="example@mail.com">
                </div>
                
                <button type="submit" class="btn-save">Сохранить email</button>
            </form>
        </div>
        
        <!-- Форма смены пароля -->
        <div class="settings-form">
            <h2>Сменить пароль</h2>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <input type="hidden" name="action" value="change_password">
                
                <div class="form-group">
                    <label for="current_password">Текущий пароль</label>
                    <input type="password" id="current_password" name="current_password" required
                           placeholder="Введите текущий пароль">
                </div>
                
                <div class="form-group">
                    <label for="new_password">Новый пароль</label>
                    <input type="password" id="new_password" name="new_password" required
                           placeholder="Минимум 6 символов">
                </div>
                
                <div class="form-group">
                    <label for="new_password_confirm">Подтвердите новый пароль</label>
                    <input type="password" id="new_password_confirm" name="new_password_confirm" required
                           placeholder="Повторите новый пароль">
                </div>
                
                <button type="submit" class="btn-save">Сменить пароль</button>
            </form>
        </div>
        
        <!-- Выход из аккаунта -->
        <div class="settings-form">
            <h2>Выход из аккаунта</h2>
            <a href="logout.php" class="btn-save" style="display: inline-block; text-decoration: none; background-color: #ff0051;">
                Выйти из аккаунта
            </a>
        </div>

        <!-- Footer -->
        <footer>
            <div class="footer-stores">
                <button class="footer-store-btn">
                    <img src="assets/img/Apple.svg" alt="App Store">
                </button>
                <button class="footer-store-btn">
                    <img src="assets/img/Play.svg" alt="Google Play">
                </button>
                <button class="footer-store-btn">
                    <img src="assets/img/Ru.svg" alt="RuStore">
                </button>
                <button class="footer-store-btn">
                    <img src="assets/img/Huaw.svg" alt="AppGallery">
                </button>
            </div>
            
            <div class="footer-links">
                <button class="footer-link">Ответы на вопросы</button>
                <button class="footer-link">Для поставщиков</button>
                <button class="footer-link">Деловая этика и противодействие коррупции</button>
                <button class="footer-link">Контакты</button>
                <button class="footer-link">Умный импорт</button>
                <button class="footer-link">Правила и соглашения</button>
                <button class="footer-link">Работа в Везет</button>
                <button class="footer-link">Стать курьером-партнёром</button>
                <button class="footer-link">Политика конфиденциальности</button>
            </div>
            
            <p class="footer-legal">
                Зона, время, товары и предложения доставки ограничены. Организатор, продавец: ООО «Умный ритейл» 
                ОГРН 1177847261802, 121087 Москва, вн.г.тер. муниципальный округ Филёвский парк, ул. Барклая, д. 8, стр. 3, помещ. 8/28.
            </p>
        </footer>
    </main>
    
    <!-- КОРЗИНА (second-aside) -->
    <div class="second-aside">
        <!-- City block -->
        <div class="city-block">
            <button class="city-button">
                Выбрать город <span class="city-arrow">▾</span>
            </button>
            <p class="city-hint">Выберите адрес, и покажем товары и акции, которые точно доступны</p>
            <button class="address-btn">Выбрать адрес</button>
        </div>

        <!-- Cart block -->
        <div class="cart-block">
            <?php if (empty($cart)): ?>
                <p class="cart-empty">Добавьте товары в корзину и мы доставим их!</p>
            <?php else: ?>
                <p class="cart-title">Корзина</p>
                
                <?php foreach ($cart as $item): ?>
                    <div class="cart-row" data-product-id="<?= $item['product']['id'] ?>">
                        <span class="cart-row-name"><?= escape($item['product']['name']) ?></span>
                        <span class="cart-row-qty">× <?= $item['quantity'] ?></span>
                        <span class="cart-row-price"><?= $item['product']['price'] * $item['quantity'] ?> ₽</span>
                        <button class="cart-remove-btn" onclick="removeFromCart(<?= $item['product']['id'] ?>)">✕</button>
                    </div>
                <?php endforeach; ?>
                
                <button class="order-btn">Заказ от <?= $cartTotal ?> ₽</button>
            <?php endif; ?>
        </div>
    </div>
    
    <script src="assets/js/cart.js"></script>
    <script src="assets/js/city.js"></script>
</body>
</html>
