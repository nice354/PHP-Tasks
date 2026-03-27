<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $login = isset($_POST['login']) ? trim($_POST['login']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $remember = isset($_POST['remember']) ? true : false;

    $errors = [];

    if (empty($login)) {
        $errors[] = "Логин не может быть пустым";
    } elseif (strlen($login) < 3) {
        $errors[] = "Логин должен содержать минимум 3 символа";
    } elseif (strlen($login) > 20) {
        $errors[] = "Логин не должен превышать 20 символов";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $login)) {
        $errors[] = "Логин может содержать только буквы, цифры и символ подчеркивания";
    }

    if (empty($password)) {
        $errors[] = "Пароль не может быть пустым";
    } elseif (strlen($password) < 6) {
        $errors[] = "Пароль должен содержать минимум 6 символов";
    }

    if (!empty($errors)) {
        $error_string = urlencode(implode("; ", $errors));
        header("Location: index.php?error=" . $error_string);
        exit();
    }

    $found = false;
    $lines = file("users.txt");
    foreach ($lines as $line) {
        $line = trim($line);
        $parts = explode(":", $line);
        if ($parts[0] == $login && $parts[1] == $password) {
            $found = true;
        }
    }

    if ($found) {
        $success_message = "Добро пожаловать, " . htmlspecialchars($login) . "!";

        if ($remember) {
            $success_message .= " Вы будете запомнены на этом устройстве.";
        }

        header("Location: index.php?success=" . urlencode($success_message));
        exit();
    } else {
        $errors[] = "Неверный логин или пароль";
        $error_string = urlencode(implode("; ", $errors));
        header("Location: index.php?error=" . $error_string);
        exit();
    }

} else {
    $error_string = urlencode("Неверный метод запроса");
    header("Location: index.php?error=" . $error_string);
    exit();
}
?>
