<?php
include(__DIR__."/helpers.php");
include(__DIR__."/config.php");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
ini_set('display_errors',1);
error_reporting(E_ALL);

$con = mysqli_connect($db_host,$db_name,$db_password,$db_database);
mysqli_set_charset($con, "utf8mb4");

$is_auth = rand(0, 1);
$user_name = 'Дмитрий';

$select_categories = 
    "SELECT categories.* 
    FROM categories";

$categorys = mysqli_fetch_all(mysqli_query($con,$select_categories),MYSQLI_ASSOC);