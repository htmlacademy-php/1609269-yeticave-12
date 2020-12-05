<?php
include(__DIR__."/bootstrap.php");

$title_name = "Добавление файл";
$tempates_name = 'add.main.php';

show_page($title_name,$tempates_name,$categorys,$is_auth,$user_name);