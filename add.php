<?php
include(__DIR__."/bootstrap.php");
if(!isset($_SESSION['user']['name'])){
    page_403($categorys,'Для доступа к данному ресурсу необходимо <a href ="login.php">авторизоваться</a>');
    exit();
}
$errors = []; 
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $errors['lot-name'] = check_input('lot-name',5,100);  
    $errors['message'] = check_input('message',5,3000);   
    $errors['category'] = check_input_category('category',$categorys);
    $errors['lot-rate'] = check_input('lot-rate',100,1000000,FILTER_VALIDATE_INT); 
    $errors['lot-step'] =  check_input('lot-step',1,1000000,FILTER_VALIDATE_INT); 
    $errors['lot-date'] = check_input_date('lot-date',1,365); 
    $errors['lot-img'] = check_input_file('lot-img',10,['jpeg','jpg','png'],['image/jpeg','image/png']);
    $errors = array_filter($errors);
    if(!$errors){
        insert_new_lot($con); 
        $id =  mysqli_insert_id($con);
        $file_name = $id.".".pathinfo(trim($_FILES['lot-img']['name']), PATHINFO_EXTENSION);
        move_file($file_name,$_FILES['lot-img']['tmp_name'],'uploads');
        $file_url = '/uploads/'.$file_name;
        update_file_link($id,$file_url,$con);
        header("Location: /lot.php?id=".$id);
        die();
    }
}    
show_page('add.html.php',"Добавление лота",['errors' => $errors],$categorys);                                              