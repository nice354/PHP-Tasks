<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_POST['submitted'])) {
    header('Location: index.php');
    exit();
}

$errors = [];

$name     = trim($_POST['name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';
$confirm  = $_POST['confirm_password'] ?? '';
$birthdate = trim($_POST['birthdate'] ?? '');
$zipcode  = trim($_POST['zipcode'] ?? '');
$city     = trim($_POST['city'] ?? '');
$snils    = trim($_POST['snils'] ?? '');
$ip       = trim($_POST['ip'] ?? '');

$_SESSION['form_data'] = compact('name', 'email', 'phone', 'birthdate', 'zipcode', 'city', 'snils', 'ip');

if (empty($name)) {
    $errors['Имя'] = 'Обязательно для заполнения';
} elseif (!preg_match('/^[a-zA-Zа-яА-ЯёЁ\s-]{2,30}$/u', $name)) {
    $errors['Имя'] = 'Только буквы, пробелы или дефисы (2–30 символов)';
}

if (empty($email)) {
    $errors['Email'] = 'Обязательно для заполнения';
} elseif (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
    $errors['Email'] = 'Введите корректный email адрес';
}

if (empty($phone)) {
    $errors['Телефон'] = 'Обязательно для заполнения';
} elseif (!preg_match('/^\+7\s\d{3}\s\d{3}-\d{2}-\d{2}$/', $phone)) {
    $errors['Телефон'] = 'Формат: +7 999 123-45-67';
}

if (empty($password)) {
    $errors['Пароль'] = 'Обязательно для заполнения';
} elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
    $errors['Пароль'] = 'Минимум 8 символов, строчные и заглавные буквы, цифра и спецсимвол (@$!%*?&)';
}

if ($password !== $confirm) {
    $errors['Подтверждение пароля'] = 'Пароли не совпадают';
}

if (empty($birthdate)) {
    $errors['Дата рождения'] = 'Обязательно для заполнения';
} elseif (!preg_match('/^\d{2}\.\d{2}\.\d{4}$/', $birthdate)) {
    $errors['Дата рождения'] = 'Формат: ДД.ММ.ГГГГ';
} else {
    [$day, $month, $year] = explode('.', $birthdate);
    if (!checkdate((int)$month, (int)$day, (int)$year)) {
        $errors['Дата рождения'] = 'Укажите корректную дату';
    } elseif ((time() - strtotime("$year-$month-$day")) / (365.25 * 24 * 3600) < 18) {
        $errors['Дата рождения'] = 'Вам должно быть не менее 18 лет';
    }
}

if (empty($zipcode)) {
    $errors['Индекс'] = 'Обязательно для заполнения';
} elseif (!preg_match('/^\d{6}$/', $zipcode)) {
    $errors['Индекс'] = 'Ровно 6 цифр';
}

if (empty($city)) {
    $errors['Город'] = 'Обязательно для заполнения';
} elseif (!preg_match('/^[a-zA-Zа-яА-ЯёЁ\s-]{2,50}$/u', $city)) {
    $errors['Город'] = 'Только буквы и дефисы (2–50 символов)';
}

if (empty($snils)) {
    $errors['СНИЛС'] = 'Обязательно для заполнения';
} elseif (!preg_match('/^\d{3}-\d{3}-\d{3} \d{2}$/', $snils)) {
    $errors['СНИЛС'] = 'Формат: XXX-XXX-XXX XX (например, 123-456-789 00)';
}

if (empty($ip)) {
    $errors['IP-адрес'] = 'Обязательно для заполнения';
} elseif (!preg_match('/^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/', $ip)) {
    $errors['IP-адрес'] = 'Введите корректный IPv4-адрес (например, 192.168.1.1)';
}

if (empty($errors)) {
    unset($_SESSION['form_data']);
    $_SESSION['registered_name']  = $name;
    $_SESSION['registered_email'] = $email;
    header('Location: success.php');
    exit();
} else {
    $_SESSION['errors'] = $errors;
    header('Location: index.php');
    exit();
}
?>
