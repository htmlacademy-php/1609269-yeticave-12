<?php
include(__DIR__."/bootstrap.php");
$errors = [];
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $errors['lot-name'] = check_input('lot-name',5,100);  
    $errors['message'] = check_input('message',5,3000);   
    $errors['category'] = check_input_category('category',$categorys);
    $errors['lot-rate'] = check_input('lot-rate',1,1000000,FILTER_VALIDATE_INT); 
    $errors['lot-step'] =  check_input('lot-step',0,1000000,FILTER_VALIDATE_INT); 
    $errors['lot-date'] = check_input_date('lot-date',1,365); 
    $errors['lot-img'] = check_input_file('lot-img',10,['jpeg','jpg','png'],['image/jpeg','image/png']);
    $errors = array_filter($errors);
    if(!$errors){
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
        prepared_query($insert_add_pos,$con,[
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
        $file_name = $id.".".pathinfo(trim($_FILES['lot-img']['name']), PATHINFO_EXTENSION);
        move_file($file_name,$_FILES['lot-img']['tmp_name'],'uploads');
        $file_url = '/uploads/'.$file_name;
        $update_file_link=
            "UPDATE lots
            SET lots.img_link = ?
            WHERE lots.id = ?";
        prepared_query($update_file_link,$con,[$file_url,$id]);
        header("Location: /lot.php?id=".$id);
        die();
    }
}          
if(!isset($_SESSION['user_name'])){
    $_SESSION['user_name'] = null;
    $is_auth = 0;
}
show_page('add.html.php',"Добавление лота",['errors' => $errors],$categorys,$is_auth,$_SESSION['user_name']);                                            