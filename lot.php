<?php
$id = $_GET['id'];

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
date_completion ,category,description, MAX(COALESCE(bids.price,lots.start_price)) + step_rate AS min_bid

FROM lots
LEFT JOIN bids
ON lots.id = bids.lot_id

LEFT JOIN categories
ON lots.id = categories.id

WHERE lots.id = $id
GROUP BY lots.id
ORDER BY lots.date_create DESC;";
$select_bids = 
"SELECT bids.*,users.name
FROM bids
JOIN users
ON users.id = bids.user_id
WHERE bids.lot_id = $id
GROUP BY bids.id
ORDER BY bids.date_create DESC;";

$products = mysqli_fetch_assoc(mysqli_query($con,$select_lots));
$categorys = mysqli_fetch_all(mysqli_query($con,$select_categories),MYSQLI_ASSOC);
$bids =  mysqli_fetch_all(mysqli_query($con,$select_bids),MYSQLI_ASSOC);

if(!$products){

$title_name = 'Файл не найден';
$is_auth = rand(0, 1);
$user_name = 'Дмитрий';

include(__DIR__."/helpers.php");
$content = include_template("404.php",[]);
$page = include_template("layout.php",['content' => $content,
                                       'is_auth' => $is_auth,
                                       'title_name' => $title_name,
                                       'user_name' => $user_name]);
print($page);
}else{

$title_name = $products['name'];
$is_auth = rand(0, 1);
$user_name = 'Дмитрий';

include(__DIR__."/helpers.php");
$content = include_template("lot.main.php",['categorys' => $categorys , 'products' =>$products, 'bids' => $bids]);
$page = include_template("layout.php",['content' => $content,
                                       'is_auth' => $is_auth,
                                       'title_name' => $title_name,
                                       'user_name' => $user_name]);
print($page);
}