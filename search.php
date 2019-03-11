<?php
require_once 'init.php';
require_once 'functions.php';
$categories = get_categories($con);

$search = $_GET['search'] ?? '';




$lot_content = include_template("search.php",
    ['search' => $lot,
        'categories' => $categories,
    ]);

print($lot_content);