<?php
require_once 'init.php';
require_once 'functions.php';
session_start();
$categories =  get_categories($con);
$user = [];
$errors = [];
if  ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = $_POST;
    $user['email'] = trim($user['email']);
    $user['name'] = trim($user['name']);
    $user['contacts'] = trim($user['contacts']);
    $errors_list = [
        'email' => 'Введите e-mail',
        'password' => 'Введите пароль',
        'name' => 'Введите имя',
        'contacts' => 'Напишите как с вами связаться',
        'bottom' => 'Пожалуйста, исправьте ошибки в форме'
        ];
    foreach ($user as $key => $value) {
        if (empty($value)) {
                $errors[$key] = $errors_list[$key];
            }
        }

if(empty($errors['mail'])) {
    if (!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "E-mail введён некорректно";
    }
    $email = check_email($con, $user['email']);
    if (!empty($email)) {
        $errors['email'] = "Пользователь с данным email уже существует";
    }
}
    if (!empty($_FILES['avatar']['name'])) {
        $file_type = mime_content_type($_FILES['avatar']['tmp_name']);
        if ($file_type !== 'image/jpeg' && $file_type !== 'image/png' && $file_type !== 'image/jpg') {
            $errors['avatar'] = "Загрузите изображение в формате jpg, jpeg, или png";
        }
    }
    if (count($errors) === 0){
        $password = password_hash($user['password'],  PASSWORD_DEFAULT);Ы
        if (!empty($_FILES['avatar']['name'])){
            $tmp_name = $_FILES['avatar']['tmp_name'];
            $path = $_FILES['avatar']['name'];
            move_uploaded_file($tmp_name, 'img/' . $path);
            $avatar = 'img/' . $path;


            $user_data = [$user['email'], $password, $user['name'], $user['contacts'], $avatar];
            $sql = "INSERT INTO users (email, password, name, contacts, avatar) VALUES ( ?, ?, ?, ?, ?)";
            $stmt = db_get_prepare_stmt($con, $sql, $user_data);
            mysqli_stmt_execute($stmt);
            }
        else {
            $user_data = [$user['email'], $password, $user['name'], $user['contacts']];
            $sql = "INSERT INTO users (email, password, name, contacts) VALUES ( ?, ?, ?, ?)";
            $stmt = db_get_prepare_stmt($con, $sql, $user_data);
            mysqli_stmt_execute($stmt);
            }
            header('Location: login.php');
        }
    else {
        $errors['bottom'] = $errors_list['bottom'];
    }
}
    $sign_up_content = include_template('sign_up.php', [
        'categories' => $categories,
        'errors' => $errors,
        'user' => $user
    ]);
    print($sign_up_content);
