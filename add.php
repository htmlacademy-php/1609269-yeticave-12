<?php
include(__DIR__."/bootstrap.php");

//Создание переменных
$errors =  ['lot-name' => true,
            'category' => true,
            'message' => true,
            'lot-rate' => true,
            'lot-step' => true,
            'lot-date' => true,
            'lot-img' => true,
            'form' => true];

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $errors['form'] = false;
    $errors['lot-name'] = check_input('lot-name','str',5,20);  
    $errors['message'] = check_input('message','str',5,3000);   
    $errors['category'] = checkCorrectCategory($_POST['category'],$categorys);
    $errors['lot-rate'] = check_input('lot-rate','int',1,1000000); 
    $errors['lot-step'] =  check_input('lot-step','int',1,1000000); 
    $errors['lot-date'] = checkCorrectDate($_POST['lot-date'],$condition = "+ 1 days"); 
    $errors['lot-img'] = checkCorrectImg($_FILES["lot-img"],10,['jpeg','jpg','png']);

    if(!is_string($errors['lot-img'])){
        move_file($_FILES['lot-img']['name'],$_FILES['lot-img']['tmp_name'],'uploads');
        $file_url = '/uploads/'.$_FILES['lot-img']['name'];
    }
    //Если все поля заполнены и равны true, форма тоже равна true и создается новый lot на sql
    if(!array_filter($errors)){
        $errors['form'] = true;
        $select_check_category_id=
        "SELECT categories.id
        FROM categories
        WHERE categories.category = ?
        ";
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
        $add_pos_query = prepared_query($insert_add_pos,$con,$passed_variables = [
                            date("Y-m-d H:i:s"),
                            $_POST['lot-name'],
                            $_POST['message'],
                            0,
                            0,
                            $_POST['category'],
                            $file_url,
                            $_POST['lot-rate'],
                            $_POST['lot-date'],
                            $_POST['lot-step']]);
        header("Location: /lot.php?id=".mysqli_insert_id($con));
        die("Нет доступа к лоту");
    }
}                                                        
show_page('add.main.php',"Добавление лота",['errors' => $errors,'categorys' => $categorys],$categorys);