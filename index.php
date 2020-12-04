<?php
include(__DIR__.'/bootstrap.php');

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

$content = include_template("main.php",['categorys' => $categorys , 'products' =>$products]);
$page = include_template("layout.php",['content' => $content,
                                       'categorys' => $categorys,
                                       'title_name' => 'Главная',
                                       'is_auth' => $is_auth,
                                       'user_name' => $user_name]);
print $page;