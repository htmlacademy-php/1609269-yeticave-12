<?php
include(__DIR__."/bootstrap.php");

//Проверить, что отправлена форма.
if(empty($_POST)){   
    echo("Не получил");
}else{
    echo("Получил данные");
    foreach($_POST as $key => $value){
        //Убедиться, что заполнены все поля.
        if(empty($value)){
            echo " ".$key." должен быть заполнен!";
        }
        //Выполнить все проверки
        if($key == "lot-name"){
            echo isCorrectLength("lot-name",5,20);  
        }

        if($key == "message"){
            echo isCorrectLength("message",0,3000);  
        }
    }
}
$title_name = "Добавление файл";
$tempates_name = 'add.main.php';

show_page($title_name,$tempates_name,$categorys,$is_auth,$user_name, $content_array = ['categorys' => $categorys]);