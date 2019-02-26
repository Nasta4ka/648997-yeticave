<?php
require_once 'init.php';
require_once 'functions.php';
$advert = get_adverts($con);


if (isset($_GET['lot_id'])) {
    $lot_id = $_GET['lot_id'];

    foreach($advert as $array)
    {
        if(in_array( "$lot_id", $array, true)) {
             $key = $lot_id;
        }
    }

    if($lot_id == $key)
    {
    $all_lots = "
    SELECT 
           lots.id,
           lots.title,
           lots.author_id,
           categories.category,
           lots.creation_date,
           lots.picture,
           lots.description,
           lots.start_price,
           lots.end_time,
           lots.rate
    FROM
         lots
    JOIN
           categories
    ON
      lots.category_id = categories.id
    WHERE lots.id = '$lot_id'";

    $lot = get_assoc($all_lots, $con);
    $lot_content = include_template('lot.php', ['lot' => $lot]);}


else {
    $lot_content = include_template('error.php', ['error' => mysqli_error($con)]);
}

 print($lot_content); }
