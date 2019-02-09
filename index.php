<?php
require_once('functions.php');

$is_auth = rand(0, 1);
$user_name = 'Nasta4ka'; // укажите здесь ваше имя
$advert = [
   [
        "name" => "2014 Rossignol District Snowboard",
        "category" => "Доски и лыжи",
        "price" => 10999,
        "pic_url" => "img/lot-1.jpg"
    ],
   [
        "name" => "DC Ply Mens 2016/2017 Snowboard",
        "category" => "Доски и лыжи",
        "price" => 159999,
        "pic_url" => "img/lot-2.jpg"
    ],
   [
        "name" => "Крепления Union Contact Pro 2015 года размер L/XL",
        "category" => "Крепления",
        "price" => 8000,
        "pic_url" => "img/lot-3.jpg"
    ],
    [
        "name" => "Ботинки для сноуборда DC Mutiny Charocal",
        "category" => "Ботинки",
        "price" => 10999,
        "pic_url" => "img/lot-4.jpg"
    ],
   [
        "name" => "Куртка для сноуборда DC Mutiny Charocal",
        "category" => "Одежда",
        "price" => 7500,
        "pic_url" => "img/lot-5.jpg"
    ],
   [
        "name" => "Маска Oakley Canopy",
        "category" => "Разное",
        "price" => 5400,
        "pic_url" => "img/lot-6.jpg"
    ]
];
$categories = array("Доски и лыжи", "Крепления", "Ботинки", "Одежда", "Инструменты", "Разное");

 function format_price($price)
{
    $price = ceil($price);
    if ($price >= 1000) {
        $price = number_format($price, 0, '', ' ');
    }
    return $price . " ₽";
};

function esc($str) {
    $text = htmlspecialchars($str);
    return $text;
};

 $page_content = include_template('index.php',
     ['categories' => $categories,
         'advert' => $advert]);
 $layout_content = include_template("layout.php",
     ['page_content' => $page_content,
         'categories' => $categories,
         'title' => 'Главная',
         'is_auth' => $is_auth,
         'user_name' => $user_name]);
print($layout_content);



?>
