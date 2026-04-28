<?php
require_once 'config.php';
require_once 'db.php';
require_once 'functions.php';

// Запуск сессии
startSecureSession();

// Получение ID товара из GET
$productId = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

// Валидация
if ($productId <= 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Неверный ID товара']);
    exit;
}

// Получение товара
$product = getProductById($productId);

if (!$product) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Товар не найден']);
    exit;
}

// Возврат данных товара
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'product' => [
        'id' => $product['id'],
        'name' => $product['name'],
        'description' => $product['description'],
        'price' => $product['price'],
        'category' => $product['category'],
        'image_path' => $product['image_path'],
        'kcal' => $product['kcal'],
        'protein' => $product['protein'],
        'fat' => $product['fat'],
        'carbo' => $product['carbo'],
        'compound' => $product['compound'],
        'expiry' => $product['expiry'],
        'producer' => $product['producer'],
        'brand' => $product['brand'],
        'product_type' => $product['product_type']
    ]
]);
