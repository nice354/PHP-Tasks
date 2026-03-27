<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Лабораторная работа: Форма авторизации</h1>
        <p>Студент: <b>Никита</b></p>
        <p>Группа: <b>09.02.07-3В</b></p>

        <h2>Регистрация</h2>

        <div class="form-container">
            <form action="register.php" method="POST" class="auth-form">
                <div class="form-group">
                    <label for="login">Логин:</label>
                    <input type="text" id="login" name="login" placeholder="Введите логин">
                    <small class="hint">От 3 до 20 символов</small>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="text" id="email" name="email" placeholder="Введите email">
                </div>

                <div class="form-group">
                    <label for="password">Пароль:</label>
                    <input type="password" id="password" name="password" placeholder="Введите пароль">
                    <small class="hint">Минимум 6 символов</small>
                </div>

                <div class="form-group">
                    <label for="password2">Подтверждение пароля:</label>
                    <input type="password" id="password2" name="password2" placeholder="Повторите пароль">
                </div>

                <button type="submit" class="btn">Зарегистрироваться</button>
                <button type="reset" class="btn btn-secondary">Очистить</button>
            </form>

            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $login = $_POST['login'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $password2 = $_POST['password2'];

                $error = "";

                if (strlen($login) < 3) {
                    $error = "Логин слишком короткий";
                } elseif (strlen($login) > 20) {
                    $error = "Логин слишком длинный";
                }

                if ($error == "" && strpos($email, "@") === false) {
                    $error = "Email введён неверно";
                }

                if ($error == "" && strlen($password) < 6) {
                    $error = "Пароль слишком короткий";
                }

                if ($error == "" && $password != $password2) {
                    $error = "Пароли не совпадают";
                }

                if ($error == "") {
                    $lines = file("users.txt");
                    foreach ($lines as $line) {
                        $parts = explode(":", trim($line));
                        if ($parts[0] == $login) {
                            $error = "Такой логин уже занят";
                        }
                    }
                }

                if ($error == "") {
                    file_put_contents("users.txt", $login . ":" . $password . "\n", FILE_APPEND);
                    echo '<div class="success-message">';
                    echo '<h3>Успешно!</h3>';
                    echo '<p>Пользователь ' . htmlspecialchars($login) . ' зарегистрирован. <a href="index.php">Войти</a></p>';
                    echo '</div>';
                } else {
                    echo '<div class="error-message">';
                    echo '<p>' . $error . '</p>';
                    echo '</div>';
                }
            }
            ?>

            <p style="margin-top: 15px;">Уже есть аккаунт? <a href="index.php">Войти</a></p>
        </div>
    </div>
</body>
</html>
