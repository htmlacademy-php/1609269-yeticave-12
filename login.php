<?php 
include(__DIR__."/bootstrap.php");
$errors = [];
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $errors['email'] = check_input_email('email');
    $errors['password'] = check_input_password('password');
    if(!array_filter($errors)){
        $select_user = 
        'SELECT users.*
         FROM users
         WHERE users.email = ?';
        $users_query = prepared_query($select_user,$con,[$_POST['email']])->get_result();
        $users = mysqli_fetch_assoc($users_query);
        if(!password_verify($_POST['password'],$users['password'])){
            $errors['password'] = "Неверный пароль!";
        }else{
            //Создание сессии
            $is_auth = 1;
            $user_name = $users['name'];
            header('Location: /index.php');
        }
    }
}
show_page('login.html.php','Вход',['errors' => $errors],$categorys,$is_auth,$user_name);