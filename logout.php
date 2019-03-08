<?php
require_once 'init.php';
session_start();
$_SESSION = [];
header("Location: /login.php");
