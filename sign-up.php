<?php
include(__DIR__.'/bootstrap.php');
if(isset($_SESSION['user']['name'])){
    page_403($categorys,"Для доступа к данному ресурсу необходимо выйти с аккаунта.");
    exit();
}
$errors = [];
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $errors['email'] = check_input('email',1,320,FILTER_VALIDATE_EMAIL);
    if(!$errors['email'] and select_user_by_email($_POST['email'],$con)){
        $errors['email'] = "Аккаунт с таким email уже есть!";
    }
    $errors['password'] = check_input('password',8,200);
    $errors['name'] = check_input('name',1,30);
    $errors['message'] = check_input('message',0,2000);
    if(!array_filter($errors)){
        insert_new_user($con);
        update_token($_POST['email'],$con);
        header('Location: '.$_SESSION['link']);
        die();
    }
}
show_page('sign-up.html.php','Регистрация нового аккаунта',['errors' => $errors],$categorys);