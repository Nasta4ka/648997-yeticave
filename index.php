<?php
date_default_timezone_set('Europe/Moscow');
require_once 'init.php';
require_once 'functions.php';

$is_auth = rand(0, 1);
$user_name = 'Nasta4ka'; // укажите здесь ваше имя
$categories = get_categories($con);
$advert = get_adverts($con);

$page_content = include_template('index.php',
    ['categories' => $categories,
        'advert' => $advert
        ]);

$layout_content = include_template("layout.php",
    ['page_content' => $page_content,
        'categories' => $categories,
        'title' => 'Главная',
        'is_auth' => $is_auth,
        'user_name' => $user_name]);

print($layout_content);