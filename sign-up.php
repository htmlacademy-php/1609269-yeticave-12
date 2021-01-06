<?php
include(__DIR__.'/bootstrap.php');
$errors = [];
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $errors['email'] = check_input('email',1,320,FILTER_VALIDATE_EMAIL);
    $errors['email'] = (empty($errors['email']) and select_user_by_email($_POST['email'],$con))?"Аккаунт с таким email уже есть!":$errors['email'];
    $errors['password'] = check_input('password',8,200);
    $errors['name'] = check_input('name',1,30);
    $errors['message'] = check_input('message',0,2000);
    if(!array_filter($errors)){
        $insert_new_user = 
       "INSERT INTO users(date_create,email,name,password,сontact)  
        VALUES (?,?,?,?,?)";
        prepared_query($insert_new_user,$con,[date("Y-m-d H:i:s"),$_POST['email'],$_POST['name'],
                                              password_hash($_POST['password'],PASSWORD_DEFAULT), $_POST['message']]);
        $_SESSION['user']['name'] = $_POST['name'];
        header('Location: /index.php');
        die();
    }
}
if(isset($_SESSION['user']['name'])){
    page_403($categorys);
}
show_page('sign-up.html.php','Регистрация нового аккаунта',['errors' => $errors],$categorys);