<?php
include(__DIR__."/bootstrap.php");
$name_status = false;
$message_status = false;
$rate_status = false;
$step_status  = false;
$date_status = false;
$con_add_status = false;
//Проверить, что отправлена форма.
if(!empty($_POST)){   
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
    $file_link =  str_replace('//','/',str_replace('\\','/',addslashes(__DIR__).$file_url));
}

if( $name_status && 
    $message_status && 
    $rate_status && 
    $step_status && 
    $date_status&&
    $file_status){
        $add_pos_query=
        "INSERT INTO lots  (date_create,
                            name,
                            description,
                            user_id,
                            winner_id,
                            category_id,
                            img_link,
                            start_price,
                            date_completion,
                            step_rate)
        VALUES (NOW(),
                '".$_POST['lot-name']."',
                '".$_POST['message']."',
                0,
                0,
                0,
                '".$file_link."',
                '".$_POST['lot-rate']."',
                '".$_POST['lot-date']."',
                '".$_POST['lot-step']."');
        ";
        $con ->query($add_pos_query);
        $con_add_status = true;
    }
$id = 0;
if($con_add_status){
    $take_id = 
    "SELECT lots.id
    FROM lots
    WHERE lots.name = '".$_POST['lot-name']."'
    ";
    $id = mysqli_fetch_assoc(mysqli_query($con,$take_id))['id'];
}
$title_name = "Добавление файл";
$tempates_name = 'add.main.php';
show_page($title_name,$tempates_name,$categorys,$is_auth,$user_name, $content_array = [
                                                                                    'id' => $id,
                                                                                    'categorys' => $categorys, 
                                                                                    'file_status' => $file_status,
                                                                                    "name_status" => $name_status,
                                                                                    "message_status" => $message_status,
                                                                                    'rate_status' => $rate_status,
                                                                                    'step_status' => $step_status,
                                                                                    'date_status' =>$date_status
                                                                                    ]);