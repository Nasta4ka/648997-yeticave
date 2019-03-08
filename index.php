<?php
date_default_timezone_set('Europe/Moscow');
require_once 'init.php';
require_once 'functions.php';
session_start();
$categories = get_categories($con);
$advert = get_adverts($con);
$page_content = include_template('index.php',
    ['categories' => $categories,
        'advert' => $advert
        ]);

$layout_content = include_template("layout.php",
    ['page_content' => $page_content,
        'categories' => $categories,
        'title' => 'Главная'
    ]);

print($layout_content);