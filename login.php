<?php
require_once 'init.php';
require_once 'functions.php';
$is_auth = rand(0, 1);
$user_name = 'Nasta4ka'; // укажите здесь ваше имя
$categories =  get_categories($con);
$errors = [];


$login_content = include_template('login.php', [
    'categories' => $categories,
    'errors' => $errors,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
]);
print($login_content);