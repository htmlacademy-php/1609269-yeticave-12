<?php
include(__DIR__."/helpers.php");
include(__DIR__."/config.php");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
ini_set('display_errors',1);
error_reporting(E_ALL);

$is_auth = rand(0, 1);
$user_name = 'Дмитрий';