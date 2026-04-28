<?php
// Корзина и выбор города
$cart = getCart();
$cartTotal = getCartTotal();
$cartCount = getCartCount();
?>

<div class="second-aside">
    <!-- City block -->
    <div class="city-block">
        <button class="city-button">
            Москва <span class="city-arrow">▾</span>
        </button>
        <p class="delivery-note">Доставка 15 минут</p>
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
