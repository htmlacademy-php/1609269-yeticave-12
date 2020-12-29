<?php
include(__DIR__.'/bootstrap.php');

$select_lots = 
    "SELECT lots.id ,name,start_price,img_link,
    MAX(COALESCE(bids.price,lots.start_price)) AS price, 
    date_completion ,category

    FROM lots
    LEFT JOIN bids
    ON lots.id = bids.lot_id

    LEFT JOIN categories
    ON lots.category_id = categories.id

    WHERE lots.date_completion >= NOW()
    GROUP BY lots.id
    ORDER BY lots.date_create DESC;";

$products = mysqli_fetch_all(mysqli_query($con,$select_lots),MYSQLI_ASSOC);

show_page("main.php","Главная",['products' =>$products],$categorys);