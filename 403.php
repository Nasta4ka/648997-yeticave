<?php
require_once 'init.php';
require_once 'functions.php';
$categories =  get_categories($con);

$error_content = include_template('403.php', [
    'categories' => $categories
]);
print($error_content);
