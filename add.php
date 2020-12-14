<?php
include(__DIR__."/bootstrap.php");
$name_status = false;
$message_status= false;
$rate_status = false;
$step_status = false;
$date_statu = false;
$file_status = false;
$lot_link = false;
$form = false;
//Проверить, что отправлена форма.
if(!empty($_POST)){   
    if(!empty($_POST["lot-name"])){$name_status = (isCorrectLength($_POST["lot-name"],5,20)) ?: false;} 
    if(!empty($_POST["message"])){$message_status=(isCorrectLength($_POST["message"],5,3000)) ?: false;}
    if(!empty($_POST["lot-rate"])){$rate_status = (isInt($_POST['lot-rate']) and isCorrectLength($_POST["lot-rate"],1,9)and $_POST["lot-rate"]>0) ?: false;};
    if(!empty($_POST["lot-step"])){$step_status = (isInt($_POST['lot-step']) and isCorrectLength($_POST["lot-step"],1,9) and $_POST["lot-rate"]>0) ?: false;};
    if(!empty($_POST["lot-date"])){$date_status = (isCorrectDate($_POST['lot-date'])) ?: false;};
}
if(!empty($_FILES)){
    $file_status = isCorrectImg($_FILES["lot-img"]);
    if($file_status){
        $file_name = $_FILES['lot-img']['name'];
        $file_path = __DIR__ . '/uploads/';
        $file_url = '/uploads/' . $file_name;
        move_uploaded_file($_FILES['lot-img']['tmp_name'], $file_path . $file_name);
}
}

if( $name_status && 
    $message_status && 
    $rate_status && 
    $step_status && 
    $date_status &&
    $file_status){
        $form = true;
        $select_check_category_id=
        "SELECT categories.id
        FROM categories
        WHERE categories.category = ?
        ";
        $insert_add_pos=
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
        VALUES (?,?,?,?,?,?,?,?,?,?);";
        $check_category_id_query = replace_in_query($select_check_category_id,$con,$_POST['category']);
        $category_id = mysqli_fetch_assoc($check_category_id_query)['id'];  
        $add_pos_query = replace_in_query($insert_add_pos,$con,[date("Y-m-d H:i:s"),
                                                                $_POST['lot-name'],
                                                                $_POST['message'],
                                                                0,
                                                                0,
                                                                $category_id,
                                                                $file_url,
                                                                $_POST['lot-rate'],
                                                                $_POST['lot-date'],
                                                                $_POST['lot-step']]);
        $lot_link = mysqli_insert_id($con);
    }
$title_name = "Добавление файл";
$tempates_name = 'add.main.php';
show_page($title_name,$tempates_name,$categorys,$is_auth,$user_name, $content_array = [
                                                                                    'categorys' => $categorys,
                                                                                    'lot_link' => $lot_link,
                                                                                    'form' => $form
                                                                                    ]);