<?php
require_once 'init.php';
require_once 'functions.php';
session_start();
$categories =  get_categories($con);
$errors = [];
$auth =[];

if  ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $auth = $_POST;
    $auth['email'] = trim($auth['email']);
    $errors_list = [
        'email' => 'Введите e-mail',
        'password' => 'Введите пароль',
    ];

    $email = $auth['email'];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "E-mail введён некорректно";
    } else {
        $email_check = check_email($con, $email);
        if (empty($email_check)) {
            $errors['email'] = "Пользователь с таким e-mail не найден";
        } else {
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $res = mysqli_query($con, $sql);
            $user = mysqli_fetch_array($res, MYSQLI_ASSOC);
        }
    }


    foreach ($auth as $key => $value) {
        if (empty($value)) {
            $errors[$key] = $errors_list[$key];
        }
    }

    if (!count($errors) and !empty($user)) {
        if (password_verify($auth['password'], $user['password'])) {
            $_SESSION['user'] = $user;
            header("Location: /index.php");

        } else {
            $errors['password'] = 'Неверный пароль';
        }
    }
}

$login_content = include_template('login.php', [
        'categories' => $categories,
        'errors' => $errors,
        'auth' => $auth
]);

print($login_content);
