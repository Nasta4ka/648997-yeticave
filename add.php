<?php
require_once 'init.php';
require_once 'functions.php';
$categories =  get_categories($con);

 if($_SERVER['REQUEST_METHOD'] == 'POST') {

     $lot = $_POST;
     $file = $_POST['image'];

     $required = ['lot-name', 'category', 'message', 'image', 'lot-rate', 'lot-step', 'lot-date'];
     //валидация
     $rules = [
         'lot-rate' => 'check_price',
         'lot-step' => 'check_price'
     ];

     $errors = [];
     foreach ($required as $key) {
         if (empty($_POST[$key])) {
             $errors[$key] = 'Это поле надо заполнить';
         }
     }

     if (isset($_FILES['image']['name'])) {
         $tmp_name = $_FILES['image']['tmp_name'];
         $path = $_FILES['image']['name'];
         $finfo = finfo_open(FILEINFO_MIME_TYPE);
         $file_type = finfo_file($finfo, $tmp_name);

         if ($file_type !== 'image/jpeg' && $file_type !== 'image/png') {
             $errors['file'] = 'Загрузите картинку в формате png или jpeg';
         }
         else {
             move_uploaded_file($tmp_name, 'uploads/' . $path);
             $lot['image'] = $path;
         }}
     else {
             $errors['file'] = 'Вы не загрузили файл';
         }



     $sql = "SELECT id FROM categories WHERE name = '$category'";
     $result = mysqli_query($con, $sql);
     if ($result) {
         return mysqli_fetch_all($result, MYSQLI_ASSOC);
     }
     $category_id = $result;
     $image_path = validate_image($file);

     //валидация...

         $sql = 'INSERT INTO lots (title, category_id, description, picture, start_price, rate, end_time) VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?)';
         $stmt = db_get_prepare_stmt($con, $sql, [$lot['lot_name'], $category_id, $lot['message'], $image_path, $lot['lot_rate'], $lot['lot_step'], $lot['lot_date']]);
         $res = mysqli_stmt_execute($stmt);
         if ($res) {
             $lot_id = mysqli_insert_id($con);

             header('Location: lot.php?lot_id=' . $lot_id);
         } else {
             $add_content = include_template('add.php', [
                 'categories' => $categories,
                 'lot' => $lot,
                 'errors' => $errors]);
         }
     }

 else {
    $add_content = include_template('add.php', [
        'categories' => $categories,
        'lot' => $lot]);

    $add_layout = include_template('layout.php', [
        'page_content' => $add_content,
        'categories' => $categories,
        'page_title' => 'Добавление лота']);
    print $add_layout;
}


