<?php 
include(__DIR__."/bootstrap.php");
$_SESSION['user']['name'] = null;
$errors = [];
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $errors['email'] = check_input('email',1,320,FILTER_VALIDATE_EMAIL);
    $user = select_user_by_email($_POST['email'],$con);
    $errors['email'] = (empty($errors['email']) and !$user)?"Аккаунта с таким email нет":$errors['email'];
    $errors['password'] = check_input('password',8,200);
    if(!array_filter($errors)){
        if(!password_verify($_POST['password'],$user['password'])){
            $errors['password'] = "Неверный пароль!";
        }else{
            update_token($_POST['email'],$con);
            header('Location: '.$_SESSION['link']);
            die();
        }
    }
}
show_page('login.html.php','Вход',['errors' => $errors],$categorys);