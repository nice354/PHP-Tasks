<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форма авторизации</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Лабораторная работа: Форма авторизации</h1>
        <p>Студент: <b>Никита</b></p>
        <p>Группа: <b>09.02.07-3В</b></p>

        <h2>Форма входа</h2>

        <div class="form-container">
            <form action="process.php" method="POST" class="auth-form">
                <div class="form-group">
                    <label for="login">Логин:</label>
                    <input type="text" id="login" name="login" required placeholder="Введите ваш логин">
                    <small class="hint">Логин должен быть от 3 до 20 символов</small>
                </div>

                <div class="form-group">
                    <label for="password">Пароль:</label>
                    <input type="password" id="password" name="password" required placeholder="Введите ваш пароль">
                    <small class="hint">Пароль должен быть от 6 символов</small>
                </div>

                <div class="form-group">
                    <label for="remember">
                        <input type="checkbox" id="remember" name="remember" value="1">
                        Запомнить меня
                    </label>
                </div>

                <button type="submit" class="btn">Войти</button>
                <button type="reset" class="btn btn-secondary">Очистить форму</button>
            </form>

            <p style="margin-top: 15px;">Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>

            <?php
            if (isset($_GET['error'])) {
                $error = htmlspecialchars($_GET['error']);
                $error_messages = explode("; ", $error);

                echo '<div class="error-message">';
                echo '<h3>Обнаружены ошибки:</h3>';
                echo '<ul>';
                foreach ($error_messages as $error_msg) {
                    echo '<li>' . $error_msg . '</li>';
                }
                echo '</ul>';
                echo '</div>';
            }

            if (isset($_GET['success'])) {
                $success = htmlspecialchars($_GET['success']);
                echo '<div class="success-message">';
                echo '<h3>Успешно!</h3>';
                echo '<p>' . $success . '</p>';
                echo '</div>';
            }
            ?>
        </div>

    </div>
</body>
</html>
