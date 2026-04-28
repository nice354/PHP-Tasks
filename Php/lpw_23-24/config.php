<?php
// config.php - настройки подключения к базе данных PostgreSQL

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/html; charset=utf-8');

// ============================================
// ПАРАМЕТРЫ ПОДКЛЮЧЕНИЯ К PostgreSQL
// ============================================

define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_USER', 'postgres');
define('DB_PASS', ''); // Укажите пароль, если есть
define('DB_NAME', 'crud_app');

// ============================================
// ФУНКЦИЯ ПОДКЛЮЧЕНИЯ
// ============================================

function getConnection() {
    $conn_string = "host=" . DB_HOST . 
                   " port=" . DB_PORT . 
                   " dbname=" . DB_NAME . 
                   " user=" . DB_USER . 
                   " password=" . DB_PASS;
    
    $conn = pg_connect($conn_string);
    
    if (!$conn) {
        die("Ошибка подключения к PostgreSQL: " . pg_last_error());
    }
    
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

// ============================================
// ФУНКЦИЯ ПОЛУЧЕНИЯ ВСЕХ ТОВАРОВ
// ============================================

function getAllProducts() {
    $conn = getConnection();
    $sql = "SELECT * FROM products ORDER BY id DESC";
    $result = pg_query($conn, $sql);
    
    $products = [];
    if ($result && pg_num_rows($result) > 0) {
        while ($row = pg_fetch_assoc($result)) {
            $products[] = $row;
        }
    }
    
    closeConnection($conn);
    return $products;
}

// ============================================
// ФУНКЦИЯ ПОЛУЧЕНИЯ ОДНОГО ТОВАРА ПО ID
// ============================================

function getProductById($id) {
    $conn = getConnection();
    $sql = "SELECT * FROM products WHERE id = $1";
    $result = pg_query_params($conn, $sql, array($id));
    
    $product = null;
    if ($result && pg_num_rows($result) > 0) {
        $product = pg_fetch_assoc($result);
    }
    
    closeConnection($conn);
    return $product;
}

// ============================================
// ФУНКЦИЯ ДОБАВЛЕНИЯ ТОВАРА
// ============================================

function createProduct($name, $description, $price, $quantity, $category) {
    $conn = getConnection();
    $sql = "INSERT INTO products (name, description, price, quantity, category) 
            VALUES ($1, $2, $3, $4, $5) RETURNING id";
    $result = pg_query_params($conn, $sql, array($name, $description, $price, $quantity, $category));
    
    $new_id = false;
    if ($result) {
        $row = pg_fetch_assoc($result);
        $new_id = $row['id'];
    }
    
    closeConnection($conn);
    return $new_id;
}

// ============================================
// ФУНКЦИЯ ОБНОВЛЕНИЯ ТОВАРА
// ============================================

function updateProduct($id, $name, $description, $price, $quantity, $category) {
    $conn = getConnection();
    $sql = "UPDATE products 
            SET name = $1, description = $2, price = $3, quantity = $4, category = $5 
            WHERE id = $6";
    $result = pg_query_params($conn, $sql, array($name, $description, $price, $quantity, $category, $id));
    
    $success = ($result !== false);
    
    closeConnection($conn);
    return $success;
}

// ============================================
// ФУНКЦИЯ УДАЛЕНИЯ ТОВАРА
// ============================================

function deleteProduct($id) {
    $conn = getConnection();
    $sql = "DELETE FROM products WHERE id = $1";
    $result = pg_query_params($conn, $sql, array($id));
    
    $success = ($result !== false);
    
    closeConnection($conn);
    return $success;
}

// ============================================
// ФУНКЦИЯ ФОРМАТИРОВАНИЯ ЦЕНЫ
// ============================================

function formatPrice($price) {
    return number_format($price, 2, ',', ' ') . ' руб.';
}

// ============================================
// ФУНКЦИЯ СТАТУСА КОЛИЧЕСТВА
// ============================================

function getQuantityStatus($quantity) {
    if ($quantity <= 0) {
        return ['class' => 'out-of-stock', 'text' => 'Нет в наличии'];
    } elseif ($quantity < 5) {
        return ['class' => 'low-stock', 'text' => 'Осталось мало: ' . $quantity];
    } else {
        return ['class' => 'in-stock', 'text' => 'В наличии: ' . $quantity];
    }
}
?>
