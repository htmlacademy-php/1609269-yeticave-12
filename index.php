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

/*WHERE lots.date_completion >= NOW()*/
GROUP BY lots.id
ORDER BY lots.date_create DESC;"),MYSQLI_ASSOC);

$categorys = mysqli_fetch_all(mysqli_query($con,
"SELECT categories.* 
FROM categories"),MYSQLI_ASSOC);

/*if ($con == false) {
    print("Ошибка подключения: " . mysqli_connect_error());
 } 
 else { 
    print("Соединение установлено"); 
 }
$products = [
    [
        'name' => '2014 Rossignol District Snowboard',
        'category' => 0,
        'price' => 10999,
        'img_link' => 'img/lot-1.jpg',
        'date' => date('Y-m-d H:i:s',time()+500),
    ],
    [
        'name' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => 0,
        'price' => 	159999,
        'img_link' => 'img/lot-2.jpg', 
        'date' => date('Y-m-d H:i:s',time()+6000),
    ],
    [
        'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => 1,
        'price' => 	8000,
        'img_link' => 'img/lot-3.jpg',
        'date' => date('Y-m-d H:i:s',time()+5000),
    ],
    [
        'name' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'category' => 2,
        'price' => 	10999,
        'img_link' => 'img/lot-4.jpg',
        'date' => date('Y-m-d H:i:s',time()+1800),
    ],
    [
        'name' => 'Куртка для сноуборда DC Mutiny Charocal',
        'category' => 3,
        'price' => 	7500,
        'img_link' => 'img/lot-5.jpg', 
        'date' => date('Y-m-d H:i:s',time()+10000),
    ],
    [
        'name' => 'Маска Oakley Canopy',
        'category' => 5,
        'price' => 	5400,
        'img_link' => 'img/lot-6.jpg',
        'date' => date('Y-m-d H:i:s',time()+1000),
    ]
];
$categorys = ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];*/
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