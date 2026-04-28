<?php
require_once 'config.php';
require_once 'db.php';

// ===== SESSION MANAGEMENT =====

/**
 * Запускает безопасную сессию с настройками безопасности
 */
function startSecureSession(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.cookie_samesite', 'Lax');
        
        session_start();
        
        // Regenerate session ID periodically
        if (!isset($_SESSION['created'])) {
            $_SESSION['created'] = time();
        } elseif (time() - $_SESSION['created'] > 1800) {
            session_regenerate_id(true);
            $_SESSION['created'] = time();
        }
    }
}

// ===== AUTHENTICATION =====

/**
 * Проверяет, авторизован ли пользователь
 * @return bool
 */
function isLoggedIn(): bool
{
    return isset($_SESSION['user_login']);
}

/**
 * Требует авторизацию, редиректит на login.php если не авторизован
 */
function requireAuth(): void
{
    if (!isLoggedIn()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: login.php?error=' . urlencode('Требуется авторизация'));
        exit;
    }
}

/**
 * Получает данные текущего пользователя из сессии
 * @return array|null
 */
function getCurrentUser(): ?array
{
    if (!isLoggedIn()) {
        return null;
    }
    
    $login = $_SESSION['user_login'];
    return dbSelectOne('SELECT id, login, name, email, role FROM users WHERE login = ?', [$login]);
}

/**
 * Создает сессию для пользователя
 * @param string $login Логин пользователя
 * @param bool $remember Запомнить пользователя
 */
function createUserSession(string $login, bool $remember = false): void
{
    $user = dbSelectOne('SELECT id, login, name, email, role FROM users WHERE login = ?', [$login]);
    
    if (!$user) {
        return;
    }
    
    $_SESSION['user_login'] = $user['login'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_role'] = $user['role'];
    
    if ($remember) {
        $token = bin2hex(random_bytes(32));
        $expires = time() + (REMEMBER_ME_DAYS * 24 * 60 * 60);
        
        setcookie('remember_me', json_encode([
            'login' => $login,
            'token' => $token,
            'expires' => $expires
        ]), $expires, '/', '', false, true);
        
        // Store token in database for validation
        dbExecute(
            'UPDATE users SET remember_token = ?, remember_expires = ? WHERE login = ?',
            [$token, date('Y-m-d H:i:s', $expires), $login]
        );
    }
}

/**
 * Уничтожает сессию и cookie
 */
function destroyUserSession(): void
{
    if (isset($_SESSION['user_login'])) {
        $login = $_SESSION['user_login'];
        
        // Clear remember token from database
        dbExecute('UPDATE users SET remember_token = NULL, remember_expires = NULL WHERE login = ?', [$login]);
    }
    
    $_SESSION = [];
    
    if (isset($_COOKIE['remember_me'])) {
        setcookie('remember_me', '', time() - 3600, '/', '', false, true);
    }
    
    session_destroy();
}

/**
 * Проверяет и восстанавливает сессию из cookie
 */
function restoreSessionFromCookie(): void
{
    if (isLoggedIn() || !isset($_COOKIE['remember_me'])) {
        return;
    }
    
    $data = json_decode($_COOKIE['remember_me'], true);
    
    if (!$data || !isset($data['login'], $data['token'], $data['expires'])) {
        return;
    }
    
    if (time() > $data['expires']) {
        setcookie('remember_me', '', time() - 3600, '/', '', false, true);
        return;
    }
    
    $user = dbSelectOne(
        'SELECT * FROM users WHERE login = ? AND remember_token = ? AND remember_expires > NOW()',
        [$data['login'], $data['token']]
    );
    
    if ($user) {
        createUserSession($user['login'], false);
    }
}

// ===== VALIDATION =====

/**
 * Валидирует логин
 * @param string $login
 * @return array ['valid' => bool, 'error' => string]
 */
function validateLogin(string $login): array
{
    if (strlen($login) < 3) {
        return ['valid' => false, 'error' => 'Логин должен содержать минимум 3 символа'];
    }
    
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $login)) {
        return ['valid' => false, 'error' => 'Логин может содержать только буквы, цифры и подчеркивание'];
    }
    
    return ['valid' => true, 'error' => ''];
}

/**
 * Валидирует пароль
 * @param string $password
 * @return array ['valid' => bool, 'error' => string]
 */
function validatePassword(string $password): array
{
    if (strlen($password) < 6) {
        return ['valid' => false, 'error' => 'Пароль должен содержать минимум 6 символов'];
    }
    
    return ['valid' => true, 'error' => ''];
}

/**
 * Валидирует имя
 * @param string $name
 * @return array ['valid' => bool, 'error' => string]
 */
function validateName(string $name): array
{
    if (strlen($name) < 2) {
        return ['valid' => false, 'error' => 'Имя должно содержать минимум 2 символа'];
    }
    
    if (!preg_match('/^[a-zA-Zа-яА-ЯёЁ\s]+$/u', $name)) {
        return ['valid' => false, 'error' => 'Имя может содержать только буквы'];
    }
    
    return ['valid' => true, 'error' => ''];
}

/**
 * Валидирует email
 * @param string $email
 * @return array ['valid' => bool, 'error' => string]
 */
function validateEmail(string $email): array
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['valid' => false, 'error' => 'Некорректный email адрес'];
    }
    
    return ['valid' => true, 'error' => ''];
}

/**
 * Валидирует данные товара
 * @param array $data
 * @return array ['valid' => bool, 'errors' => array]
 */
function validateProduct(array $data): array
{
    $errors = [];
    
    if (empty($data['name']) || strlen($data['name']) < 3) {
        $errors['name'] = 'Название должно содержать минимум 3 символа';
    }
    
    if (empty($data['price']) || $data['price'] <= 0) {
        $errors['price'] = 'Цена должна быть положительным числом';
    }
    
    if (empty($data['category']) || !in_array($data['category'], CATEGORIES)) {
        $errors['category'] = 'Выберите корректную категорию';
    }
    
    return [
        'valid' => empty($errors),
        'errors' => $errors
    ];
}


// ===== CART MANAGEMENT =====

/**
 * Получает корзину из сессии
 * @return array
 */
function getCart(): array
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    $cart = [];
    foreach ($_SESSION['cart'] as $item) {
        $product = getProductById($item['product_id']);
        if ($product) {
            $cart[] = [
                'product' => $product,
                'quantity' => $item['quantity']
            ];
        }
    }
    
    return $cart;
}

/**
 * Добавляет товар в корзину
 * @param int $productId ID товара
 * @param int $quantity Количество
 */
function addToCart(int $productId, int $quantity = 1): bool
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] === $productId) {
            $item['quantity'] += $quantity;
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        $_SESSION['cart'][] = [
            'product_id' => $productId,
            'quantity' => $quantity
        ];
    }
    
    return true;
}

/**
 * Удаляет товар из корзины
 * @param int $productId ID товара
 */
function removeFromCart(int $productId): bool
{
    if (!isset($_SESSION['cart'])) {
        return false;
    }
    
    $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) use ($productId) {
        return $item['product_id'] !== $productId;
    });
    
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    return true;
}

/**
 * Обновляет количество товара в корзине
 * @param int $productId ID товара
 * @param int $quantity Новое количество
 */
function updateCartQuantity(int $productId, int $quantity): bool
{
    if (!isset($_SESSION['cart'])) {
        return false;
    }
    
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] === $productId) {
            $item['quantity'] = max(1, $quantity);
            return true;
        }
    }
    
    return false;
}

/**
 * Получает общую стоимость корзины
 * @return float
 */
function getCartTotal(): float
{
    $cart = getCart();
    $total = 0;
    
    foreach ($cart as $item) {
        $total += $item['product']['price'] * $item['quantity'];
    }
    
    return $total;
}

/**
 * Получает общее количество товаров в корзине
 * 
 * @return int
 */
function getCartCount(): int
{
    $cart = getCart();
    $count = 0;
    
    foreach ($cart as $item) {
        $count += $item['quantity'];
    }
    
    return $count;
}

/**
 * Очищает корзину
 */
function clearCart(): void
{
    $_SESSION['cart'] = [];
}

// ===== PRODUCT MANAGEMENT =====

/**
 * Получает все товары или с фильтром по категории
 * @param string|null $category Категория для фильтрации
 * @return array
 */
function getProducts(?string $category = null): array
{
    if ($category) {
        return dbSelect('SELECT * FROM products WHERE category = ? ORDER BY created_at DESC', [$category]);
    }
    
    return dbSelect('SELECT * FROM products ORDER BY created_at DESC');
}

/**
 * Получает товар по ID
 * @param int $id ID товара
 * @return array|null
 */
function getProductById(int $id): ?array
{
    return dbSelectOne('SELECT * FROM products WHERE id = ?', [$id]);
}

/**
 * Создает новый товар
 * @param array $data Данные товара
 * @return int|false ID нового товара или false
 */
function createProduct(array $data): int|false
{
    $query = 'INSERT INTO products (name, description, price, category, image_path, kcal, protein, fat, carbo, compound, expiry, producer, brand, product_type) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
    
    $params = [
        $data['name'],
        $data['description'] ?? null,
        $data['price'],
        $data['category'],
        $data['image_path'] ?? null,
        $data['kcal'] ?? null,
        $data['protein'] ?? null,
        $data['fat'] ?? null,
        $data['carbo'] ?? null,
        $data['compound'] ?? null,
        $data['expiry'] ?? null,
        $data['producer'] ?? null,
        $data['brand'] ?? null,
        $data['product_type'] ?? null
    ];
    
    if (dbExecute($query, $params)) {
        return dbLastInsertId();
    }
    
    return false;
}

/**
 * Обновляет товар
 * @param int $id ID товара
 * @param array $data Новые данные
 * @return bool
 */
function updateProduct(int $id, array $data): bool
{
    $fields = [];
    $params = [];
    
    foreach ($data as $key => $value) {
        if (in_array($key, ['name', 'description', 'price', 'category', 'image_path', 'kcal', 'protein', 'fat', 'carbo', 'compound', 'expiry', 'producer', 'brand', 'product_type'])) {
            $fields[] = "$key = ?";
            $params[] = $value;
        }
    }
    
    if (empty($fields)) {
        return false;
    }
    
    $params[] = $id;
    $query = 'UPDATE products SET ' . implode(', ', $fields) . ', updated_at = CURRENT_TIMESTAMP WHERE id = ?';
    
    return dbExecute($query, $params);
}

/**
 * Удаляет товар
 * @param int $id ID товара
 * @return bool
 */
function deleteProduct(int $id): bool
{
    // Delete image file if exists
    $product = getProductById($id);
    if ($product && $product['image_path']) {
        $filePath = UPLOAD_DIR . basename($product['image_path']);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
    
    return dbExecute('DELETE FROM products WHERE id = ?', [$id]);
}

/**
 * Поиск товаров
 * @param string $query Поисковый запрос
 * @return array
 */
function searchProducts(string $query): array
{
    $searchTerm = '%' . $query . '%';
    return dbSelect(
        'SELECT * FROM products WHERE name ILIKE ? OR description ILIKE ? ORDER BY name',
        [$searchTerm, $searchTerm]
    );
}

// ===== FILE UPLOAD =====

/**
 * Загружает изображение товара
 * @param array $file Файл из $_FILES
 * @return array ['success' => bool, 'path' => string, 'error' => string]
 */
function uploadProductImage(array $file): array
{
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Ошибка загрузки файла'];
    }
    
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'error' => 'Файл слишком большой (макс. 5MB)'];
    }
    
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mimeType, $allowedTypes)) {
        return ['success' => false, 'error' => 'Недопустимый тип файла'];
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('product_', true) . '.' . $extension;
    $destination = UPLOAD_DIR . $filename;
    
    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }
    
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => true, 'path' => UPLOAD_URL . $filename];
    }
    
    return ['success' => false, 'error' => 'Не удалось сохранить файл'];
}

// ===== CSRF PROTECTION =====

/**
 * Генерирует CSRF токен
 * @return string
 */
function generateCsrfToken(): string
{
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * Проверяет CSRF токен
 * @param string $token Токен для проверки
 * @return bool
 */
function verifyCsrfToken(string $token): bool
{
    return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

// ===== HELPERS =====

/**
 * Экранирует строку для безопасного вывода
 * @param string $str Строка для экранирования
 * @return string
 */
function escape(string $str): string
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * Редирект на другую страницу
 * @param string $url URL для редиректа
 * @param array $params GET параметры
 */
function redirect(string $url, array $params = []): void
{
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }
    
    header('Location: ' . $url);
    exit;
}
