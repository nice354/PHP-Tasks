<?php
require_once 'config.php';
session_start();

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $quantity = intval($_POST['quantity'] ?? 0);
    $category = trim($_POST['category'] ?? '');
    
    // Валидация
    if (empty($name)) {
        $error = 'Название товара обязательно';
    } elseif ($price <= 0) {
        $error = 'Цена должна быть больше 0';
    } elseif ($quantity < 0) {
        $error = 'Количество не может быть отрицательным';
    } else {
        $result = createProduct($name, $description, $price, $quantity, $category);
        
        if ($result) {
            $_SESSION['success_message'] = 'Товар «' . htmlspecialchars($name) . '» успешно добавлен!';
            header('Location: index.php');
            exit();
        } else {
            $error = 'Ошибка при добавлении товара';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить товар</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>➕ Добавление товара</h1>
        <div class="subtitle">Заполните все поля формы</div>
        
        <div class="menu">
            <a href="index.php" class="btn">← Назад к списку</a>
        </div>
        
        <?php if ($error): ?>
            <div class="error-message">❌ <?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="form-container">
            <form method="POST">
                <div class="form-group">
                    <label>Название товара *</label>
                    <input type="text" name="name" required 
                           value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label>Категория</label>
                    <select name="category">
                        <option value="">Выберите категорию</option>
                        <option value="Электроника">Электроника</option>
                        <option value="Одежда">Одежда</option>
                        <option value="Книги">Книги</option>
                        <option value="Другое">Другое</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Цена (руб) *</label>
                    <input type="number" step="0.01" name="price" required 
                           value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label>Количество *</label>
                    <input type="number" name="quantity" required 
                           value="<?php echo htmlspecialchars($_POST['quantity'] ?? '0'); ?>">
                </div>
                
                <div class="form-group">
                    <label>Описание</label>
                    <textarea name="description"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">💾 Сохранить товар</button>
            </form>
        </div>
    </div>
</body>
</html>
