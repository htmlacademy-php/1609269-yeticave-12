<?php
require __DIR__."/helpers.php";
require __DIR__."/form_validation.php";
require __DIR__."/sql_models.php";
require __DIR__."/config.php";
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
ini_set('display_errors',1);
error_reporting(E_ALL);
$con = mysqli_connect($db_host,$db_name,$db_password,$db_database);
mysqli_set_charset($con, "utf8mb4");

session_start();
//Создние якоря для переадресация пользователя на последнюю открытую им страницу перед логином или регистрацией
$_SESSION['link'] = (!isset($_SESSION['link'])) ? "index.php" : $_SESSION['link'];
$_SESSION['link'] = (in_array($_SERVER['REQUEST_URI'],["/login.php","/sign-up.php","/logout.php"]))? $_SESSION['link']:$_SERVER['REQUEST_URI'];

if(!isset($_SESSION['user']['name']) and isset($_COOKIE['login']) and isset($_COOKIE['auth_token'])){
    $_SESSION['user'] = select_user_by_token($_COOKIE['login'],$_COOKIE['auth_token'],$con);
}

//Определитель победителя
$sql_query =
"SELECT id 
FROM lots 
WHERE date_completion < NOW() AND winner_id = 0;

SELECT user_id,MAX(price) 
FROM bids 
WHERE lot_id = (SELECT id FROM lots WHERE date_completion < NOW() AND winner_id = 0)
GROUP BY price AND user_id;

UPDATE lots
SET winner_id = user_id";
prepared_query($sql_query,$con);

$select_categories =
    "SELECT categories.*
    FROM categories";
$result = $con->query($select_categories);
$categorys = [];
while ($row = $result -> fetch_assoc()){
    $categorys[$row['id']] = $row;
}