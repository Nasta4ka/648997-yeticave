<?php
$con = mysqli_connect('localhost', 'root', '', '648997-yeticave');

if ($con === false) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit;
}

mysqli_set_charset($con, 'utf8');
