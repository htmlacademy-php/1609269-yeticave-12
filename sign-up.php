<?php
include(__DIR__.'/bootstrap.php');
$errors = [];
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $errors['email'] = check_input_email('email');
    $errors['password'] = check_input('password',8,20);
    $errors['name'] = check_input('name',4,10);
    $errors['message'] = check_input('message',5,100);
    if(!array_filter($errors)){
    }
}
show_page('sign-up.html.php','Регистрация нового аккаунта',['errors' => $errors],$categorys,$is_auth,$user_name);