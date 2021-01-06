<?php 
include(__DIR__."/bootstrap.php");
$_SESSION['user']['name'] = null;
$errors = [];
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $errors['email'] = check_input('email',1,320,FILTER_VALIDATE_EMAIL);
    $errors['email'] = (empty($errors['email']) and !select_user_by_email($_POST['email'],$con))?"Аккаунта с таким email нет":$errors['email'];
    $errors['password'] = check_input('password',8,200);
    if(!array_filter($errors)){
        $user = select_user_by_email($_POST['email'],$con);
        if(!password_verify($_POST['password'],$user['password'])){
            $errors['password'] = "Неверный пароль!";
        }else{
            $auth_token = bin2hex(random_bytes(30));
            update_token($auth_token,$_POST['email'],$con);
            setcookie("login",$user['email'],strtotime('+1 years'),"/");
            setcookie("auth_token",$auth_token,strtotime('+1 years'),"/");
            $_SESSION['user'] = $user;
            header('Location: '.$_SESSION['link']);
            die();
        }
    }
}
show_page('login.html.php','Вход',['errors' => $errors],$categorys);