<?php
require_once 'init.php';
require_once 'functions.php';
$categories =  get_categories($con);


if  ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lot = $_POST;
    $required = ['lot-name', 'category', 'message', 'image', 'lot-rate', 'lot-step', 'lot-date'];
    $errors_list = [
        'lot-name' => 'Введите наименование лота',
        'category' => 'Выберите категорию',
        'message' => 'Напишите описание лота',
        'image' => 'Загрузите изображение в формате png/jpeg',
        'lot-rate' => 'Введите начальную цену',
        'lot-step' => 'Введите шаг ставки',
        'lot-date' => 'Введите дату завершения торгов',
        'bottom' => 'Пожалуйста, исправьте ошибки в форме.'
    ];
    $errors = [];
    $lot_name = $lot['lot-name'];
    $lot_rate = intval($lot['lot-rate']);
    $lot_step = intval($lot['lot-step']);
    $lot_date = $lot['lot-date'];

    if (!is_numeric($lot_rate) && !$lot_rate > 0) {
        $errors['lot-rate'] = $errors_list['lot_rate'];
        exit;
    }
    if (!is_numeric($lot_step) && !$lot_step > 0) {
        $errors['lot-step'] = $errors_list['lot-step'];
        exit;
    }

    if (isset($_FILES['image']['name'])) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $path = $_FILES['image']['name'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);
        if ($file_type !== 'image/jpeg' && $file_type !== 'image/png') {
            $errors['image'] = $errors_list['image'];
        } else {
            move_uploaded_file($tmp_name, 'img/' . $path);
            $lot['image'] = $path;
        }
    } else {
        $errors['image'] = $errors_list['image'];
    }

    foreach ($lot as $key => $value) {
        if ($value = '') {
            $errors[$key] = $errors_list[$key];
        }
    }

    if (count($errors)) {
        $add_content = include_template('add.php', [
            'lot' => $lot,
            'errors' => $errors,
            'categories' => $categories]);
    } else {
        $category_name = $lot['category'];
        $category_id = get_category_id($con, $category_name);

        $lot_data = [$lot['lot-name'], $category_id, $lot['message'], $path, $lot_rate, $lot_step, $lot['lot-date']];
        var_dump($lot_data);


        $sql = "INSERT INTO lots (title, category_id, description, picture, start_price, rate, end_time) VALUES ( ?, ?, ?, ?, ?, ?, ?)";
        $stmt = db_get_prepare_stmt($con, $sql, [$lot_data]);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if ($res) {
            $lot_id = mysqli_insert_id($con);
            header('Location: lot.php?lot_id=' . $lot_id);
        }
    }
}

else {

    $add_content = include_template('add.php', [
        'categories' => $categories]);
    print($add_content);
}