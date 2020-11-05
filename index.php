<?php 
$products = [
    [
        'name' => '2014 Rossignol District Snowboard',
        'category' => 0,
        'price' => 10999,
        'img_link' => 'img/lot-1.jpg'
    ],
    [
        'name' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => 0,
        'price' => 	159999,
        'img_link' => 'img/lot-2.jpg' 
    ],
    [
        'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => 1,
        'price' => 	8000,
        'img_link' => 'img/lot-3.jpg' 
    ],
    [
        'name' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'category' => 2,
        'price' => 	10999,
        'img_link' => 'img/lot-4.jpg' 
    ],
    [
        'name' => 'Куртка для сноуборда DC Mutiny Charocal',
        'category' => 3,
        'price' => 	7500,
        'img_link' => 'img/lot-5.jpg' 
    ],
    [
        'name' => 'Маска Oakley Canopy',
        'category' => 5,
        'price' => 	5400,
        'img_link' => 'img/lot-6.jpg' 
    ]
];
$categorys = ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];
$title_name = "Главная";
$main = "templates/main.php";
$is_auth = rand(0, 1);
$user_name = 'Дмитрий';

function price_format($price){
    return number_format(ceil($price),0,'',' ') . ' ₽'; 
}

require(__DIR__ . "/helpers.php");
include(__DIR__ . "/templates/layout.php");


$first = ['31231','ggrge','rge'];
$second = ['trhbr','32424','ferfge'];
echo $summ = ['category' => $categorys , 'product' =>$products]['product'][1]['name'];
print($content = include_template('main.php', $data = ['category' => $categorys , 'product' =>$products]));
//$page = include_template('layout.php',['main' => $content, 'title' => 'Главная');
//echo $page;
?>