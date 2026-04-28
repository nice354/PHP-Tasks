<?php
// config.php - настройки подключения к базе данных PostgreSQL

// Включаем отображение ошибок (для отладки)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Устанавливаем кодировку
header('Content-Type: text/html; charset=utf-8');

// ============================================
// ПАРАМЕТРЫ ПОДКЛЮЧЕНИЯ К БАЗЕ ДАННЫХ PostgreSQL
// ============================================

// Адрес сервера PostgreSQL
define('DB_HOST', 'localhost');

// Порт PostgreSQL (по умолчанию 5432)
define('DB_PORT', '5432');

// Имя пользователя базы данных
define('DB_USER', 'postgres');

// Пароль (если пароля нет, оставьте пустую строку)
define('DB_PASS', '');

// Имя базы данных
define('DB_NAME', 'test_db');

// ============================================
// ФУНКЦИЯ ПОДКЛЮЧЕНИЯ К БД
// ============================================

function getConnection() {
    // Строка подключения для PostgreSQL
    $conn_string = "host=" . DB_HOST . 
                   " port=" . DB_PORT . 
                   " dbname=" . DB_NAME . 
                   " user=" . DB_USER . 
                   " password=" . DB_PASS;
    
    // Создаем подключение
    $conn = pg_connect($conn_string);
    
    // Проверяем подключение
    if (!$conn) {
        die("Ошибка подключения к PostgreSQL: " . pg_last_error());
    }
    
    // Устанавливаем кодировку для русского языка
    pg_set_client_encoding($conn, "UTF8");
    
    return $conn;
}

// ============================================
// ФУНКЦИЯ ЗАКРЫТИЯ ПОДКЛЮЧЕНИЯ
// ============================================

function closeConnection($conn) {
    if ($conn) {
        pg_close($conn);
    }
}
?>
