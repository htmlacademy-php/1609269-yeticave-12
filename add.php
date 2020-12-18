<?php
include(__DIR__."/bootstrap.php");
    
if(!$is_auth){
    page_404($is_auth,$categorys,$user_name);
}

//Создание переменных
$no_empty_fields = false;
$result = [];
$lot_link = 0;
$errors =  ['lot-name' => 1,
            'category' => 1,
            'message' => 1,
            'lot-rate' => 1,
            'lot-step' => 1,
            'lot-date' => 1,
            'lot-img' => 1];
$form = true;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    foreach($errors as $key => $error){
        $error = false;
    }
    $form = false;

    //Если поле "Наименование" заполнено - начинает его проверку 
    if(!empty($_POST["lot-name"])){   
        $errors['lot-name'] = isCorrectLength($_POST['lot-name'],5,20);  
    }else{
        $errors['lot-name'] = "Обязательное поле!";
    }

    //Если поле "Описание" заполнено - начинает его проверку           
    if(!empty($_POST["message"])){ 
      $errors['message'] = isCorrectLength($_POST['message'],5,3000);       
    }else{
        $errors['message'] = "Обязательное поле!";
    }  

    //Если поле "Категория" заполнено - начинает его проверку 
    if(!empty($_POST["category"])){
        if(empty($categorys[$_POST["category"]])){
            $errors['category'] = 'Неправильно выбрана категория';
        }else{
            $errors['category'] = false;
        }   
    }else{
        $errors['category'] = "Обязательное поле!";
    }

    //Если поле "Начальная цена" заполнено - начинает его проверку 
    if(!empty($_POST["lot-rate"])){
        $errors['lot-rate'] = checkInt($_POST['lot-rate'],1,1000000);
    }else{
        $errors['lot-rate'] = "Обязательное поле!";
    }

    //Если поле "Шаг ставки" заполнено - начинает его проверку 
    if(!empty($_POST["lot-step"])){
        $errors['lot-step'] = checkInt($_POST['lot-step'],1,1000000);
    }else{
        $errors['lot-step'] = "Обязательное поле!";
    } 

    //Если поле "Дата окончания торгов " заполнено - начинает его проверку 
    if(!empty($_POST["lot-date"])){
        $errors['lot-date'] = isCorrectDate($date = $_POST['lot-date'],$condition = "+ 1 days");    
    }else{
        $errors['lot-date'] = "Обязательное поле!";
    } 

    //Если поле "Изображение" заполнено - начинает его проверку 
    if(!empty($_FILES["lot-img"]['name'])){     
    $errors['lot-img'] = isCorrectImg($_FILES["lot-img"],10,['jpeg','jpg','png']);
        if(!is_string($errors['lot-img'])){
            move_file($_FILES['lot-img']['name'],$_FILES['lot-img']['tmp_name'],'uploads');
            $file_url = '/uploads/'.$_FILES['lot-img']['name'];
        }
    }else{
        $errors["lot-img"] = "Обязательное поле!";
    }
    
    //Если все поля заполнены и равны true, форма тоже равна true и создается новый lot на sql
    if(!array_filter($errors)){
        $form = true;
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
        $check_category_id_query = replace_in_query($select_check_category_id,$con,$passed_variables = [$_POST['category']]);
        $category_id = mysqli_fetch_assoc($check_category_id_query)['id'];  
        $add_pos_query = replace_in_query($insert_add_pos,$con,$passed_variables = [
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
        $lot_link = mysqli_insert_id($con);
        header("Location: /lot.php?id=".$lot_link);
    }
}                                                                       
$title_name = "Добавление файл";
$tempates_name = 'add.main.php';
show_page($title_name,$tempates_name,$categorys,$is_auth,$user_name, $content_array = [
                                                                                'form' => $form,
                                                                                'errors' => $errors,
                                                                                'categorys' => $categorys,
                                                                                'lot_link' => $lot_link,
                                                                                ]);     