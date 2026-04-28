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
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

// Валидация
if ($productId <= 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Неверный ID товара']);
    exit;
}

if ($quantity < 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Неверное количество']);
    exit;
}

// Если количество 0, удаляем товар
if ($quantity === 0) {
    $result = removeFromCart($productId);
} else {
    // Обновление количества
    $result = updateCartQuantity($productId, $quantity);
}

if ($result) {
    $cartCount = getCartCount();
    $cartTotal = getCartTotal();
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => 'Корзина обновлена',
        'cart_count' => $cartCount,
        'cart_total' => $cartTotal
    ]);
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Ошибка при обновлении корзины']);
}
