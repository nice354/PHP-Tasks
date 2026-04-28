<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_NAME', 'vezet_shop');
define('DB_USER', 'postgres');
define('DB_PASS', ''); // Пустой пароль

// Application settings
define('SITE_URL', 'http://localhost');
define('UPLOAD_DIR', __DIR__ . '/uploads/products/');
define('UPLOAD_URL', '/uploads/products/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

// Session settings
define('SESSION_LIFETIME', 3600); // 1 hour
define('REMEMBER_ME_DAYS', 30);

// Security
define('CSRF_TOKEN_NAME', 'csrf_token');

// Categories
define('CATEGORIES', ['Milk', 'Bread', 'Meat', 'Water']);
define('CATEGORY_LABELS', [
    'Milk' => 'Молоко, яйца и сыр',
    'Bread' => 'Хлеб и выпечка',
    'Meat' => 'Мясо и рыба',
    'Water' => 'Вода и напитки'
]);
