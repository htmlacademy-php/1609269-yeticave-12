<?php
include(__DIR__.'/bootstrap.php');
$products = select_lots($con);
show_page("main.php","Главная",['products' =>$products],$categorys);