<?php
include(__DIR__."/bootstrap.php");
$name_status = false;
$message_status = false;
$rate_status = false;
$step_status  = false;
$date_status = false;

//Проверить, что отправлена форма.
if(empty($_POST)){   
    echo("Не получил данные");
}else{
    echo("Получил данные");
    foreach($_POST as $key => $value){
        //Убедиться, что заполнены все поля.
        if($key == "lot-name"){ $name_status = (isCorrectLength("lot-name",5,20)) ? true : false;} 
        if($key == "message"){$message_status =  (isCorrectLength("message",5,3000)) ? true : false;}
        if($key == "lot-rate"){$rate_status = (isInt('lot-rate') and isCorrectLength("lot-rate",1,9)and $value>0) ? true : false;};
        if($key == "lot-step"){$step_status = (isInt('lot-step') and isCorrectLength("lot-step",1,9) and $value>0) ? true : false;};
        if($key == "lot-date"){$date_status = (isCorrectDate('lot-date')) ? true : false;};
    }
}
$file_status = isCorrectImg("lot-img");
if($file_status){
    $file_name = $_FILES['lot-img']['name'];
    $file_path = __DIR__ . '/uploads/';
    $file_url = '/uploads/' . $file_name;
    move_uploaded_file($_FILES['lot-img']['tmp_name'], $file_path . $file_name);
}

if( $name_status && 
    $message_status && 
    $rate_status && 
    $step_status && 
    $date_status&&
    $file_status){
        
    }

$title_name = "Добавление файл";
$tempates_name = 'add.main.php';
show_page($title_name,$tempates_name,$categorys,$is_auth,$user_name, $content_array = [
                                                                                    'categorys' => $categorys, 
                                                                                    'file_status' => $file_status,
                                                                                    "name_status" => $name_status,
                                                                                    "message_status" => $message_status,
                                                                                    'rate_status' => $rate_status,
                                                                                    'step_status' => $step_status,
                                                                                    'date_status' =>$date_status
                                                                                    ]);