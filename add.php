<?php
include(__DIR__."/bootstrap.php");
    
if(!$is_auth){
    page_404($is_auth,$categorys,$user_name);
}

//Создание переменных
$no_empty_fields = false;
$title_name = "Добавление файл";
$result = [];
$lot_link = 0;
$errors =  ['lot-name' => 0,
            'category' => 0,
            'message' => 0,
            'lot-rate' => 0,
            'lot-step' => 0,
            'lot-date' => 0,
            'lot-img' => 0];
$form = true;

//Проверка POST и FILES на элементы
if($_SERVER['REQUEST_METHOD'] != 'POST') {
    $no_empty_fields = true;
}else{
    $no_empty_fields = false;
    $form = true;
    foreach($errors as $key => $value){ //Так как 1 и более полей заполнено, меняет переменным статус всех ошибок полей = true
        $errors[$key] = true;
    }
}

//Именно эта переменная проверяет все поля
if (check_no_empty_post_and_files(array_keys($errors),['lot-img'])){ 
    $all_fields_filled = true;
}else{
    $all_fields_filled = false;
}

//Если поле "Наименование" заполнено - начинает его проверку 
if(!empty($_POST["lot-name"])){   
    $errors['lot-name'] = isCorrectLength($_POST['lot-name'],5,20);  
}


//Если поле "Описание" заполнено - начинает его проверку           
if(!empty($_POST["message"])){ 
    $errors['message'] = isCorrectLength($_POST['message'],5,3000);       
}                                                                       

//Если поле "Категория" заполнено - начинает его проверку 
if(!empty($_POST["category"])){
    $errors['category'] = true;
    foreach($categorys as $category){
        if($_POST["category"] !=  $category["category"]){
            $errors['category'] = 'Неправильно выбрана категория';
        }else{
            $errors['category'] = true;
            break;
        } 
    }     
} 

//Если поле "Начальная цена" заполнено - начинает его проверку 
if(!empty($_POST["lot-rate"])){
    $errors['lot-rate'] = checkInt($_POST['lot-rate'],1,1000000);
} 

//Если поле "Шаг ставки" заполнено - начинает его проверку 
if(!empty($_POST["lot-step"])){
    $errors['lot-step'] = checkInt($_POST['lot-step'],1,1000000);
} 

//Если поле "Дата окончания торгов " заполнено - начинает его проверку 
if(!empty($_POST["lot-date"])){
    $errors['lot-date'] = isCorrectDate($date = $_POST['lot-date'],$condition = "+ 1 days");    
}

//Если поле "Изображение" заполнено - начинает его проверку 
if(!empty($_FILES["lot-img"]['name'])){     
    $errors['lot-img'] = isCorrectImg($_FILES["lot-img"],10,['jpeg','jpg','png']);
    if(!is_string($errors['lot-img'])){
        move_file($_FILES['lot-img']['name'],$_FILES['lot-img']['tmp_name'],'uploads');
        $file_url = '/uploads/'.$_FILES['lot-img']['name'];
    }
}

//Если хотя бы 1 поле заполнено и если есть хотя бы 1 ошибка все поля, кроме не пустых, получают статус false и ошибку 'Обязательное поле!'
if($no_empty_fields==false and
   array_filter($errors)){
        foreach($_POST as $key => $value){
            $errors[$key] = (!empty($_POST[$key])) ? $errors[$key] :'Обязательное поле!';
        }
        $errors['lot-img'] = (!empty($_FILES['lot-img']['name'])) ? $errors['lot-img']: 'Обязательное поле!'; 
        $form = false;
}

//Если все поля заполнены и равны true, форма тоже равна true и создается новый lot на sql
if($all_fields_filled and
   array_filter($errors) == false){
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
                            $category_id,
                            $file_url,
                            $_POST['lot-rate'],
                            $_POST['lot-date'],
                            $_POST['lot-step']]);
        $lot_link = mysqli_insert_id($con);
}

//Открытие страницы
if($lot_link != 0){
    header("Location: /lot.php?id=".$lot_link);}
$tempates_name = 'add.main.php';
show_page($title_name,$tempates_name,$categorys,$is_auth,$user_name, $content_array = [
                                                                                    'form' => $form,
                                                                                    'errors' => $errors,
                                                                                    'categorys' => $categorys,
                                                                                    'lot_link' => $lot_link,
                                                                                    ]);