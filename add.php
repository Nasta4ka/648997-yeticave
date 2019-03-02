<?php
require_once 'init.php';
require_once 'functions.php';
$is_auth = rand(0, 1);
$user_name = 'Nasta4ka'; // укажите здесь ваше имя
$categories =  get_categories($con);
$lot = [];
$errors = [];


if  ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lot = $_POST;
    $required = ['title', 'category_id', 'description', 'picture', 'start_price', 'rate', 'end_time'];
    $errors_list = [
        'title' => 'Введите наименование лота',
        'category_id' => 'Выберите категорию',
        'description' => 'Напишите описание лота',
        'picture' => 'Загрузите изображение в формате png/jpeg',
        'start_price' => 'Введите начальную цену',
        'rate' => 'Введите шаг ставки',
        'end_time' => 'Введите дату завершения торгов',
        'bottom' => 'Пожалуйста, исправьте ошибки в форме.'
    ];


    foreach ($lot as $key => $value) {
        if (empty($value)) {
            $errors[$key] = $errors_list[$key];
        }
    }

    if (!check_category($con, $lot['category_id'])) {
        $errors['category_id'] = "Выберите категорию";
    }
    if (intval($lot['start_price']) < 0) {
        $errors['start_price'] = "Введите положительное число";
    }
    if (intval($lot['rate']) < 0) {
        $errors['rate'] = "Введите положительное число";
    }

    if (strtotime($lot['end_time']) - time() <= 60 * 60 * 24) {
        $errors['end_time'] = "Указанная дата должна быть больше текущей даты хотя бы на один день.";
    }

    if (empty($_FILES['picture']['name'])) {
        $errors['picture'] = $errors_list['picture'];
    } else {
        $file_type = mime_content_type($_FILES['picture']['tmp_name']);
        if ($file_type !== 'image/jpeg' && $file_type !== 'image/png' && $file_type !== 'image/jpg') {
            $errors['picture'] = $errors_list['picture'];
        }
    }

    if (count($errors) === 0) {
        $tmp_name = $_FILES['picture']['tmp_name'];
        $path = $_FILES['picture']['name'];

        move_uploaded_file($tmp_name, 'img/' . $path);
        $lot['picture'] = 'img/' . $path;
        $lot_data = [$lot['title'], $lot['category_id'], $lot['description'], $path, $lot['start_price'], $lot['rate'], $lot['end_time']];

        $sql = "INSERT INTO lots (title, category_id, description, picture, start_price, rate, end_time, author_id) VALUES ( ?, ?, ?, ?, ?, ?, ?, 1)";
        $stmt = db_get_prepare_stmt($con, $sql, $lot_data);
        mysqli_stmt_execute($stmt);

        $lot_id = mysqli_insert_id($con);
        header('Location: lot.php?lot_id=' . $lot_id);
    }
}




    $add_content = include_template('add.php', [
        'categories' => $categories,
        'errors' => $errors,
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'lot' => $lot]);
    print($add_content);
