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
    $errors['lot-img'] = check_correct_img('lot-img',10,['jpeg','jpg','png']);
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
                            'None',
                            $_POST['lot-rate'],
                            $_POST['lot-date'],
                            $_POST['lot-step']]);            
        $id =  mysqli_insert_id($con);
        $file_name = $id.".ext.".pathinfo(trim($_FILES['lot-img']['name']), PATHINFO_EXTENSION);
        move_file($file_name,$_FILES['lot-img']['tmp_name'],'uploads');
        $file_url = '/uploads/'.$file_name;
        $update_file_link=
        "UPDATE lots
        SET lots.img_link = ?
        WHERE lots.id = ?";
        $add_pos_query = prepared_query($update_file_link,$con,$passed_variables =[$file_url,mysqli_insert_id($con)]);
        header("Location: /lot.php?id=".$id);
        die();
    }
}          
show_page('add.main.php',"Добавление лота",['errors' => $errors],$categorys);                                            