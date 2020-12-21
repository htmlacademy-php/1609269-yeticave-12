<?php
include(__DIR__."/bootstrap.php");
$errors = [];
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $errors['lot-name'] = check_input('lot-name',5,20);  
    $errors['message'] = check_input('message',5,3000);   
    $errors['category'] = check_input_category('category',$categorys);
    $errors['lot-rate'] = check_input('lot-rate',1,1000000,FILTER_VALIDATE_INT); 
    $errors['lot-step'] =  check_input('lot-step',1,1000000,FILTER_VALIDATE_INT); 
    $errors['lot-date'] = check_input_date('lot-date',1,365); 
    $errors['lot-img'] = (!empty($_FILES['lot-img']['name']) ? check_correct_img($_FILES['lot-img'],10,['jpeg','jpg','png']) : "Обязательное поле"); 
    if(!is_string($errors['lot-img'])){
        move_file($_FILES['lot-img']['name'],$_FILES['lot-img']['tmp_name'],'uploads');
        $file_url = '/uploads/'.$_FILES['lot-img']['name'];
    }
    $errors = array_filter($errors);
    if(!$errors){
        $errors['form'] = false;
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
        $add_pos_query = prepared_query($insert_add_pos,$con,$passed_variables = [
                            date("Y-m-d H:i:s"),
                            $_POST['lot-name'],
                            $_POST['message'],
                            0,
                            0,
                            $_POST['category'],
                            $file_url,
                            $_POST['lot-rate'],
                            $_POST['lot-date'],
                            $_POST['lot-step']]);
        header("Location: /lot.php?id=".mysqli_insert_id($con));
        die();
    }
}          
show_page('add.main.php',"Добавление лота",['errors' => $errors],$categorys);                                            