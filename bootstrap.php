<?php
require __DIR__."/vendor/autoload.php";
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

// Конфигурация траспорта
#$transport = (new Swift_SmtpTransport('phpdemo.ru', 25))
#                                     ->setUsername('keks@phpdemo.ru')
#                                     ->setPassword('htmlacademy');
// Формирование сообщения
#$message = new Swift_Message("Просмотры вашей гифки");
#$message->setSubject('Ваша ставка победила');
#$message->setFrom("keks@phpdemo.ru","Yeti Cave");

#$result = '';
#ob_start();
#extract([$_SESSION['user']['name'],]);
#require __DIR__."/email.php";
#result = ob_get_clean();

#$message->setTo(["apgrayedd@mail.ru"]);
#$message->setBody();

// Отправка сообщения
#$mailer = new Swift_Mailer($transport);
#$mailer->send($message);


$select_categories =
    "SELECT categories.*
    FROM categories";
$result = $con->query($select_categories);
$categorys = [];
while ($row = $result -> fetch_assoc()){
    $categorys[$row['id']] = $row;
}