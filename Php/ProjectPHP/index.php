<?php
require_once 'config.php';
require_once 'db.php';
require_once 'functions.php';

// Запуск сессии и восстановление из cookie
startSecureSession();
restoreSessionFromCookie();

// Получение данных пользователя (если авторизован)
$user = isLoggedIn() ? getCurrentUser() : null;

// Получение параметров из GET
$category = isset($_GET['category']) ? $_GET['category'] : null;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Получение товаров
if (!empty($search)) {
    $products = searchProducts($search);
    $pageTitle = 'Результаты поиска';
} elseif ($category && in_array($category, CATEGORIES)) {
    $products = getProducts($category);
    $pageTitle = CATEGORY_LABELS[$category];
} else {
    $products = getProducts();
    $pageTitle = 'Все товары';
}

// Получение корзины
$cart = getCart();
$cartTotal = getCartTotal();
$cartCount = getCartCount();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Везет - <?= escape($pageTitle) ?></title>
    <link rel="shortcut icon" href="assets/img/Logo.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    
    <?php include 'partials/header.php'; ?>
    
    <?php include 'partials/aside.php'; ?>
    
    <main>
        <?php if ($category): ?>
            <!-- Breadcrumb для категории -->
            <p class="breadcrumb">Главная</p>
            <h1 class="main-title"><?= escape($pageTitle) ?></h1>
        <?php elseif (!empty($search)): ?>
            <!-- Заголовок для поиска -->
            <h1 class="main-title">Результаты поиска: "<?= escape($search) ?>"</h1>
        <?php else: ?>
            <!-- Баннер и приветствие для главной -->
            <h1 class="main-banner">Доставка до 30 минут!</h1>
            <?php if ($user): ?>
                <div class="welcome-block">
                    <p class="welcome-text">Добро пожаловать, <?= escape($user['name']) ?>!</p>
                </div>
            <?php endif; ?>
            <h2 class="main-subtitle">Популярное</h2>
        <?php endif; ?>
        
        <?php if (empty($products)): ?>
            <!-- Сообщение если товары не найдены -->
            <div class="no-products">
                <p>Ничего не найдено</p>
            </div>
        <?php else: ?>
            <!-- Сетка товаров -->
            <div class="products-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card" data-product-id="<?= $product['id'] ?>">
                        <div class="product-img-wrap">
                            <img src="<?= escape($product['image_path']) ?>" 
                                 class="product-img" 
                                 alt="<?= escape($product['name']) ?>">
                        </div>
                        <p class="product-name">
                            <?php 
                            // Подсветка поискового запроса
                            if (!empty($search)) {
                                echo str_ireplace($search, '<mark>' . escape($search) . '</mark>', escape($product['name']));
                            } else {
                                echo escape($product['name']);
                            }
                            ?>
                        </p>
                        <p class="product-price"><?= $product['price'] ?> ₽</p>
                        <button class="add-to-cart-btn" 
                                onclick="event.stopPropagation(); addToCart(<?= $product['id'] ?>, this)">
                            Добавить в корзину
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
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
