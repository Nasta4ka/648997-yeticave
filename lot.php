<?php
require_once 'init.php';
require_once 'functions.php';
session_start();
$categories = get_categories($con);


if (!isset($_GET['lot_id'])) {
    $lot_content = include_template("error.php",

        ['error' => mysqli_error($con),
            'page_content' => '',
            'categories' => $categories,
            'title' => 'Главная'
        ]);

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
            'title' => 'Главная'
        ]);

    print($lot_content);
    exit;
}


$user_logged = isset($_SESSION['user']) ? $_SESSION['user'] : NULL ;
$user_id = intval($user_logged['id']);
$bids = get_bids($con, $lot_id);
$errors = [];
$hide = NULL;
$bid_users = get_bid_users($con, $lot['id']);


 if (!empty($bid_users)) {
     foreach ($bid_users as $item => $value){
         $item = array_flip($value);
         foreach ($item as $k => $v) {
             if($user_id === $k) {
                 $hide = 1;
             }
         }
     }
 }

if ($user_id === $lot['author_id']) {
    $hide = 1;
}
if (strtotime($lot['end_time']) < time()) {
    $hide = 1;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($user_logged)) {
    $bid = $_POST;
    $sum = intval($bid['sum']);
    $min_sum = isset($lot['max_price']) ? esc($lot['max_price']) + $lot['rate'] : esc($lot['start_price']) + $lot['rate'];

    if (empty($sum)) {
        $errors['sum'] = 'Заполните поле';
    }
    else {
        if ($sum < $min_sum) {
            $errors['sum'] = 'Ставка должна быть не меньше минимальной';
        }
    }

    if (count($errors) === 0) {
        $bid_data = [$sum, $user_id, $lot['id']];
        $sql = "INSERT INTO bids (sum, user_id, lot_id) VALUES ( ?, ?, ?)";
        $stmt = db_get_prepare_stmt($con, $sql, $bid_data);
        mysqli_stmt_execute($stmt);
        header('Location: lot.php?lot_id=' . $lot_id);
    }
}


$lot_content = include_template("lot.php",
    ['lot' => $lot,
        'categories' => $categories,
        'title' => $lot['title'],
        'errors' => $errors,
        'bids' => $bids,
        'hide' => $hide
    ]);

print($lot_content);
