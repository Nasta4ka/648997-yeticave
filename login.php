<?php
require_once 'init.php';
require_once 'functions.php';
$categories =  get_categories($con);
$errors = [];
$auth =[];
$errors_list = [
    'email' => 'Введите e-mail',
    'password' => 'Введите пароль',
];

if  ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $auth = $_POST;
    foreach ($auth as $key => $value) {
        if (empty($value)) {
            $errors[$key] = $errors_list[$key];
        }
    }

    if(empty($errors['email'])) {
        $email = trim($auth['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "E-mail введён некорректно";
        } else if (empty(check_email($con, $email))) {
                $errors['email'] = "Пользователь с таким e-mail не найден";
            } else {
                $email = mysqli_real_escape_string($con, $email);
                $sql = "SELECT * FROM users WHERE email = '$email'";
                $res = mysqli_query($con, $sql);
                $user = mysqli_fetch_array($res, MYSQLI_ASSOC);
            }
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

$login_content = include_template('login.php', [
        'categories' => $categories,
        'errors' => $errors,
        'auth' => $auth
]);

print($login_content);
