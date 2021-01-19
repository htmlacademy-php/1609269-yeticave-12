<?php
include(__DIR__.'/bootstrap.php');
$lots = select_lots($con);
show_page("main.php","Главная",['lots' =>$lots],$categorys);