<?php
require_once 'init.php';
require_once 'functions.php';
$categories = get_categories($con);

if (!isset($_GET['lot_id'])) {
      $lot_content = include_template("error.php",

        ['error' => mysqli_error($con),
            'page_content' => '',
            'categories' => $categories,
            'title' => 'Главная',
            'is_auth' => $is_auth,
            'user_name' => $user_name]);

    print($lot_content);
    exit;
}

$lot_id = intval($_GET['lot_id']);
$lot = get_lot_id($con, $lot_id);
if (empty($lot)) {
    $lot_content = include_template("error.php",

        ['error' => mysqli_error($con),
            'page_content' => '',
            'categories' => $categories,
            'title' => 'Главная',
            'is_auth' => $is_auth,
            'user_name' => $user_name]);

    print($lot_content);
    exit;
}

$lot_content = include_template("lot.php",
    ['lot' => $lot,
        'categories' => $categories,
        'title' => $lot['title'],
        'is_auth' => $is_auth,
        'user_name' => $user_name]);

 print($lot_content);
