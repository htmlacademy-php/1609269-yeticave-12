<?php
$con = mysqli_connect("localhost","root","","yeticave");
mysqli_set_charset($con, "utf8mb4");

$products = mysqli_fetch_all(mysqli_query($con,
"SELECT lots.id ,name,start_price,img_link,
MAX(COALESCE(bids.price,lots.start_price)) AS price, 
date_completion ,category

FROM lots
LEFT JOIN bids
ON lots.id = bids.lot_id

LEFT JOIN categories
ON lots.id = categories.id

GROUP BY lots.id
ORDER BY lots.date_create DESC;"),MYSQLI_ASSOC);

$categorys = mysqli_fetch_all(mysqli_query($con,
"SELECT categories.* 
FROM categories"),MYSQLI_ASSOC);

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