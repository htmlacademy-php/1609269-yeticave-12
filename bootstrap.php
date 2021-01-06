<?php
include(__DIR__."/helpers.php");
include(__DIR__."/config.php");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
ini_set('display_errors',1);
error_reporting(E_ALL);

$con = mysqli_connect($db_host,$db_name,$db_password,$db_database);
mysqli_set_charset($con, "utf8mb4");

session_start();

if(isset($_GET['un_login'])){
    un_login(['auth_token','login'],['name','id',]);
    header("Location: /".$_SESSION['link']);
    die();
}
$_SESSION['link'] = (!isset($_SESSION['link'])) ? "index.php" : $_SESSION['link'];
$_SESSION['link'] =  ($_SERVER['REQUEST_URI'] == "/login.php" or $_SERVER['REQUEST_URI'] == "/sign-up.php") ?$_SESSION['link']:str_replace("/","",($_SERVER['REQUEST_URI']));
$_SESSION['un_login'] = $_SESSION['link'].((stristr($_SESSION['link'],"?")) ? "&un_login":"?un_login");

if(!isset($_SESSION['user']['name']) and isset($_COOKIE['login']) and isset($_COOKIE['auth_token'])){
    $user = select_user_by_token($_COOKIE['login'],$_COOKIE['auth_token'],$con);
    if($user){
        $_SESSION['user'] = $user;
    }
}
$select_categories =
    "SELECT categories.*
    FROM categories";
$result = $con->query($select_categories);
$categorys = [];
while ($row = $result -> fetch_assoc()){
    $categorys[$row['id']] = $row;
}