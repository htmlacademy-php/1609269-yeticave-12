<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
ini_set('display_errors',1);
error_reporting(E_ALL);

$con = mysqli_connect("localhost","root","","yeticave");
mysqli_set_charset($con, "utf8mb4");

$select_categories = 
"SELECT categories.* 
FROM categories";
$select_lots = 
"SELECT lots.id ,name,start_price,img_link,
MAX(COALESCE(bids.price,lots.start_price)) AS price, 
date_completion ,category

FROM lots
LEFT JOIN bids
ON lots.id = bids.lot_id

LEFT JOIN categories
ON lots.id = categories.id

WHERE lots.date_completion >= NOW()
GROUP BY lots.id
ORDER BY lots.date_create DESC;";

$products = mysqli_fetch_all(mysqli_query($con,$select_lots),MYSQLI_ASSOC);
$categorys = mysqli_fetch_all(mysqli_query($con,$select_categories),MYSQLI_ASSOC);

$title_name = "Главная";
$main = "templates/main.php";
$is_auth = rand(0, 1);
$user_name = 'Дмитрий';

include(__DIR__ . "/helpers.php");
$content = include_template("main.php",['categorys' => $categorys , 'products' =>$products]);
$page = include_template("layout.php",['content' => $content, 
                                       'title_name' => 'Главная',
                                       'is_auth' => $is_auth,
                                       'user_name' => $user_name]);
print $page;