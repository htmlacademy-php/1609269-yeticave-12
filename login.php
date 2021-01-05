<?php 
include(__DIR__."/bootstrap.php");
$_SESSION['user_name'] = null;
$is_auth = 0;
$errors = [];
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $errors['email'] = check_input('email',1,320,FILTER_VALIDATE_EMAIL);
    $errors['email'] = (empty($errors['email']) and !select_user_by_email($_POST['email'],$con))?"Аккаунта с таким email нет":$errors['email'];
    $errors['password'] = check_input('password',8,200);
    if(!array_filter($errors)){
        $select_user = 
        'SELECT users.*
         FROM users
         WHERE users.email = ?';
        $user_query = prepared_query($select_user,$con,[$_POST['email']])->get_result();
        $user = mysqli_fetch_assoc($user_query);
        if(!password_verify($_POST['password'],$user['password'])){
            $errors['password'] = "Неверный пароль!";
        }else{
            $auth_token = bin2hex(random_bytes(30));
            $update_token = 
           "UPDATE users
            SET auth_token = ?
            WHERE email = ?";
            prepared_query($update_token,$con,[$auth_token,$_POST['email']]);
            setcookie("login",$user['email'],strtotime('+1 years'),"/");
            setcookie("auth_token",$auth_token,strtotime('+1 years'),"/");
            $is_auth = 1;
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['id'] = $user['id'];
            header('Location: '.$_SESSION['link']);
            die();
        }
    }
}
show_page('login.html.php','Вход',['errors' => $errors],$categorys,$is_auth,$_SESSION['user_name']);