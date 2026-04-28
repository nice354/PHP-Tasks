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

// Проверка роли администратора
if ($user['role'] !== 'admin') {
    die('<h1>Доступ запрещен</h1><p>У вас нет прав для доступа к этой странице.</p><a href="index.php">Вернуться на главную</a>');
}

$error = '';
$success = '';

// Обработка POST запроса (создание товара)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = 'Неверный CSRF токен';
    } else {
        $productData = [
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'price' => (float)($_POST['price'] ?? 0),
            'category' => $_POST['category'] ?? '',
            'kcal' => (float)($_POST['kcal'] ?? 0),
            'protein' => 0,
            'fat' => 0,
            'carbo' => (float)($_POST['carbo'] ?? 0),
            'compound' => trim($_POST['compound'] ?? ''),
            'expiry' => trim($_POST['expiry'] ?? ''),
            'producer' => trim($_POST['producer'] ?? ''),
            'brand' => trim($_POST['brand'] ?? ''),
            'product_type' => $_POST['category'] ?? ''
        ];
        
        $validationErrors = validateProduct($productData);
        
        if (!empty($validationErrors)) {
            $error = implode('<br>', $validationErrors);
        } else {
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = uploadProductImage($_FILES['image']);
                if ($uploadResult['success']) {
                    $imagePath = $uploadResult['path'];
                } else {
                    $error = $uploadResult['error'];
                }
            }
            
            if (empty($error)) {
                if (!$imagePath) {
                    $error = 'Необходимо загрузить изображение товара';
                } else {
                    $productData['image_path'] = $imagePath;
                    
                    if (createProduct($productData)) {
                        $success = 'Товар "' . escape($productData['name']) . '" добавлен';
                    } else {
                        $error = 'Ошибка при создании товара';
                    }
                }
            }
        }
    }
}

// Обработка удаления товара
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = 'Неверный CSRF токен';
    } else {
        $name = trim($_POST['name'] ?? '');
        $producer = trim($_POST['producer'] ?? '');
        $brand = trim($_POST['brand'] ?? '');
        $description = trim($_POST['description'] ?? '');
        
        if (empty($name)) {
            $error = 'Введите название товара';
        } else {
            $allProducts = getProducts();
            $found = null;
            
            foreach ($allProducts as $product) {
                $nameMatch = stripos($product['name'], $name) !== false;
                $producerMatch = empty($producer) || stripos($product['producer'], $producer) !== false;
                $brandMatch = empty($brand) || stripos($product['brand'], $brand) !== false;
                $descMatch = empty($description) || stripos($product['description'], $description) !== false;
                
                if ($nameMatch && $producerMatch && $brandMatch && $descMatch) {
                    $found = $product;
                    break;
                }
            }
            
            if ($found) {
                if (deleteProduct($found['id'])) {
                    $success = 'Товар "' . escape($found['name']) . '" удалён';
                } else {
                    $error = 'Ошибка при удалении товара';
                }
            } else {
                $error = 'Товар не найден';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель - Везет</title>
    <link rel="shortcut icon" href="assets/img/Logo.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <button class="log-out-button" onclick="window.location.href='index.php'">Выйти</button>

    <div class="all-admin-content">

        <h1>Добавить товар</h1>
        <h1>Удалить товар</h1>

        <!-- ADD -->
        <form class="add-admin-container" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <input type="hidden" name="action" value="add">

            <input type="text"   name="name"        placeholder="Название"       required>
            <input type="text"   name="description" placeholder="Объём"          required>
            <input type="number" name="price"        placeholder="Цена (₽)"       step="0.01" required>

            <div class="div-kcal-carbo">
                <input type="number" name="kcal"  placeholder="Ккал"      step="0.1">
                <input type="number" name="carbo" placeholder="Углеводы"  step="0.1">
            </div>

            <textarea name="compound" placeholder="Состав"></textarea>

            <div class="div-data-type">
                <input type="text" name="expiry" placeholder="Срок годности">
                <select name="category" required>
                    <option value="Milk">Молоко, яйца, сыр</option>
                    <option value="Bread">Хлеб и выпечка</option>
                    <option value="Meat">Мясо и рыба</option>
                    <option value="Water">Вода и напитки</option>
                </select>
            </div>

            <input type="text" name="producer" placeholder="Производитель">
            <input type="text" name="brand"    placeholder="Бренд">
            <input type="file" name="image"    accept="image/*" class="input-img" required>

            <p class="admin-feedback<?= (isset($_POST['action']) && $_POST['action'] === 'add' && $error) ? ' error' : '' ?>">
                <?php if (isset($_POST['action']) && $_POST['action'] === 'add'): ?>
                    <?= $error ? escape($error) : escape($success) ?>
                <?php endif; ?>
            </p>

            <button type="submit">Добавить</button>
        </form>

        <!-- DELETE -->
        <form class="del-admin-container" method="POST">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <input type="hidden" name="action" value="delete">

            <input type="text" name="name"        placeholder="Название"       required>
            <input type="text" name="producer"    placeholder="Производитель">
            <input type="text" name="brand"       placeholder="Бренд">
            <input type="text" name="description" placeholder="Объём">

            <p class="admin-feedback<?= (isset($_POST['action']) && $_POST['action'] === 'delete' && $error) ? ' error' : '' ?>">
                <?php if (isset($_POST['action']) && $_POST['action'] === 'delete'): ?>
                    <?= $error ? escape($error) : escape($success) ?>
                <?php endif; ?>
            </p>

            <button type="submit">Удалить</button>
        </form>

    </div>

    <script>
        setTimeout(() => {
            document.querySelectorAll('.admin-feedback').forEach(el => el.innerText = '');
        }, 3000);
    </script>
</body>
</html>
