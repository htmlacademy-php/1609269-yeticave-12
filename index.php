<?php 
$products = [
    [
        'name' => '2014 Rossignol District Snowboard',
        'category' => 0,
        'price' => 10999,
        'img_link' => 'img/lot-1.jpg',
        'date' => '2020-11-11'
    ],
    [
        'name' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => 0,
        'price' => 	159999,
        'img_link' => 'img/lot-2.jpg', 
        'date' => '2020-12-05'
    ],
    [
        'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => 1,
        'price' => 	8000,
        'img_link' => 'img/lot-3.jpg',
        'date' => '2021-02-07'
    ],
    [
        'name' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'category' => 2,
        'price' => 	10999,
        'img_link' => 'img/lot-4.jpg',
        'date' => '2023-05-16'
    ],
    [
        'name' => 'Куртка для сноуборда DC Mutiny Charocal',
        'category' => 3,
        'price' => 	7500,
        'img_link' => 'img/lot-5.jpg', 
        'date' => '2020-11-11'
    ],
    [
//        'name' => 'Маска Oakley Canopy',
        'name' => 'CYBERPUNK 2077',
        'category' => 5,
//        'price' => 	5400,
        'price' => 	2000,
        'img_link' => 'img/cyberp.jpg', 
//        'img_link' => 'img/lot-6.jpg',
        'date' => '2020-12-10'
    ]
];
$categorys = ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];
$title_name = "Главная";
$main = "templates/main.php";
$is_auth = rand(0, 1);
$user_name = 'Дмитрий';

include(__DIR__ . "/helpers.php");
$content = include_template("main.php",['categorys' => $categorys , 'products' =>$products]);
$page = include_template("layout.php",['content' => $content, 'title_name' => 'Главная']);
print $page;