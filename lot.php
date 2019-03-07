<?php
require_once 'init.php';
require_once 'functions.php';
$categories = get_categories($con);
$lot_id = intval($_GET['lot_id']);
$lot = get_lot_id($con, $lot_id);
$user_logged = isset($_SESSION['user']) ? $_SESSION['user'] : NULL ;
$user_id = intval($user_logged['id']);
$bids = get_bids($con, $lot_id);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($user_logged)) {
    $bid = $_POST;
    $errors = [];
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

    if ($user_id === $lot['author_id']) {
        $errors['sum'] = 'Вы не можете ставить на свой лот';
    }

    if (count($errors) === 0) {
        $bid_data = [$sum, $user_id, $lot['id']];
        $sql = "INSERT INTO bids (sum, user_id, lot_id) VALUES ( ?, ?, ?)";
        $stmt = db_get_prepare_stmt($con, $sql, $bid_data);
        mysqli_stmt_execute($stmt);
        header('Location: lot.php?lot_id=' . $lot_id);
    }
}


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

$lot_content = include_template("lot.php",
    ['lot' => $lot,
        'categories' => $categories,
        'title' => $lot['title'],
        'errors' => $errors,
        'bids' => $bids
    ]);

print($lot_content);
