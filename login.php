<?php 
include(__DIR__."/bootstrap.php");
$errors = [];
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $errors['email'] = check_input_email('email',$con,'users','email','login');
    $errors['password'] = check_input_password('password');
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
            $is_auth = 1;
            $_SESSION['user_name'] = $user['name'];
            header('Location: /index.php');
        }
    }
}
print($_SERVER['HTTPS']);
if(empty($_SESSION['user_name'])){
    $_SESSION['user_name'] = 'user_name';
    $is_auth = 0;
}
show_page('login.html.php','Вход',['errors' => $errors],$categorys,$is_auth,$_SESSION['user_name']);