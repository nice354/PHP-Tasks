<?php
require_once 'config.php';
require_once 'db.php';
require_once 'functions.php';

// Запуск сессии
startSecureSession();

// Проверка метода запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Неверный метод запроса']);
    exit;
}

// Получение данных из POST
$productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

// Валидация
if ($productId <= 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Неверный ID товара']);
    exit;
}

// Удаление из корзины
$result = removeFromCart($productId);

if ($result) {
    $cartCount = getCartCount();
    $cartTotal = getCartTotal();
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => 'Товар удален из корзины',
        'cart_count' => $cartCount,
        'cart_total' => $cartTotal
    ]);
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Ошибка при удалении товара']);
}
