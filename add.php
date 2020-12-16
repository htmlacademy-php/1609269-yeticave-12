<?php
include(__DIR__."/bootstrap.php");
//Проверка авторизации
if($is_auth == 1){
    $is_auth_status = true;}
else{
    $is_auth_status = false;}
    
//Создание переменных
$no_empty_fields = false;
$title_name = "Добавление файл";
$reuslt = [];
$lot_link = 0;
$error = '';
$errors =  ['lot-name' => "",
            'category' => '',
            'message' => '',
            'lot-rate' => '',
            'lot-step' => '',
            'lot-date' => '',
            'lot-img' => '',
            'form' => ''];
$status =  ['lot-name' => $name_status = true,
            'category' => $category_status = true,
            'message' => $message_status = true, 
            'lot-rate' => $rate_status = true, 
            'lot-step' => $step_status = true, 
            'lot-date' => $date_status = true, 
            'lot-img' => $file_status = true, 
            'form' => $form = true];
$empty_status = [   'lot-name' => true,
                    'category' => true,
                    'message' => true, 
                    'lot-rate' => true, 
                    'lot-step' => true, 
                    'lot-date' => true, 
                    'lot-img' => true];
//Проверка POST и FILES на элементы, если они пустые, то форма равнa true
if(empty(array_filter(array_values($_POST))) and empty(array_filter(array_values($_FILES)))) {
    $no_empty_fields = true;
}else{
    $no_empty_fields = false;
}

//Так как 1 и более полей заполнено, меняет переменным статус на false и начинает проверку
if($no_empty_fields == false){
    for($i=0;$i++;count($status)){
        $status[$i] = false;
    }
}

//Если поле "Наименование" заполнено - начинает его проверку 
if(!empty($_POST["lot-name"])){
    $empty_status['lot-name'] = false;
    $reuslt = isCorrectLength($_POST["lot-name"],5,20);  //проверка длины 
    if($reuslt ['status']){$status['lot-name'] = true;}
                    else{$status['lot-name'] = false;
                         $errors['lot-name'] = $reuslt['error'];}              
}else{
        $status['lot-name'] = true;
}

//Если поле "Описание" заполнено - начинает его проверку                                                                                       
if(!empty($_POST["message"])){
    $empty_status['message'] = false;
    $reuslt = isCorrectLength($_POST["message"],5,3000); //проверка длины 
    if($reuslt['status']){$status['message'] = true;}
                     else{$status['message'] = false;$errors['message'] = $reuslt['error'];}              
}else{
    $status['message'] = true;
}

//Если поле "Категория" заполнено - начинает его проверку 
if(!empty($_POST["category"])){
    $empty_status['category'] = false;
    foreach($categorys as $category){
        if($_POST["category"] !=  $category["category"]){
            $errors['category'] = 'Неправильно выбрана категория';
            $status['category'] = false;
        }else{
            $status['category'] = true;
            break;
        } 
    }         
}else{
    $status['category'] = true;
}  

//Если поле "Начальная цена" заполнено - начинает его проверку 
if(!empty($_POST["lot-rate"])){
    $empty_status['lot-rate'] = false;
    $reuslt[0] = isCorrectLength($_POST['lot-rate'],1,9);    //проверка длины 
    $reuslt[1] = isInt($_POST['lot-rate']);                  //проверка типа
    $reuslt[2] = check_condition($_POST['lot-rate'],'>',0);  //проверка: подходит ли под условие
    $i = 0;
    while($i < count($reuslt)){
        if($reuslt[$i]['status']){$status[$i]['lot-rate'] = true;}
                             else{$status[$i]['lot-rate'] = false;
                                  $errors['lot-rate'] = $errors['lot-rate'].$reuslt[$i]['error']."<br>";}
        $i++;
    }
    $status['lot-rate'] = ($status[0]['lot-rate'] && $status[1]['lot-rate'] && $status[2]['lot-rate']) ? true: false;
}else{
    $status['lot-rate'] = true;
}  

//Если поле "Шаг ставки" заполнено - начинает его проверку 
if(!empty($_POST["lot-step"])){
    $empty_status['lot-step'] = false;
    $reuslt[0] = isCorrectLength($_POST['lot-step'],1,9);    //проверка длины 
    $reuslt[1] = isInt($_POST['lot-step']);                  //проверка типа
    $reuslt[2] = check_condition($_POST['lot-step'],'>',0);  //проверка: подходит ли под условие
    $i = 0;
    while($i < count($reuslt)){
        if($reuslt[$i]['status']){$status[$i]['lot-step'] = true;}
                             else{$status[$i]['lot-step'] = false;
                                  $errors['lot-step'] = $errors['lot-step'].$reuslt[$i]['error']."<br>";}
        $i++;
    }
    $status['lot-step'] = ($status[0]['lot-step'] && $status[1]['lot-step'] && $status[2]['lot-step']) ? true: false;
}else{
    $status['lot-step'] = true;
}  

//Если поле "Дата окончания торгов " заполнено - начинает его проверку 
if(!empty($_POST["lot-date"])){
    $empty_status['lot-date'] = false;
    $reuslt = isCorrectDate($date = $_POST['lot-date'],$condition = "+ 1 days");  //проверка даты: подходит ли под условие
    if($reuslt['status']){$status['lot-date'] = true;}
                     else{$status['lot-date'] = false;$errors['lot-date'] = $reuslt['error'];}           
}else{
    $status['lot-date'] = true;
}  
//Если поле "Изображение" заполнено - начинает его проверку 
if(!empty($_FILES["lot-img"]['name'])){     
    $empty_status['lot-img'] = false;                          
    $reuslt = isCorrectImg($_FILES["lot-img"],5,['jpeg','jpg','png']);            //проверка файла: подходит ли под условие
    if($reuslt['status']){$status['lot-img'] = true;
                          $file_name = $_FILES['lot-img']['name'];                //Если поле "Изображение" прошло проверку на true: перемещает файл в необходимый каталог
                          $file_path = __DIR__ . '/uploads/';
                          $file_url = '/uploads/' . $file_name;
                          move_uploaded_file($_FILES['lot-img']['tmp_name'], $file_path . $file_name);}
                     else{$status['lot-img'] = false;$errors['lot-img'] = $reuslt['error'];}      
}else{
    $status['lot-img'] = true;
}
//Если хотя бы 1 поле заполнено и если есть хотя бы 1 ошибка все поля, кроме не пустых, получают статус false и ошибку 'Обязательное поле!'
if(check_array_by_condition($empty_status,false,"or") and
   check_array_by_condition($status,false,"or")){
        $status['form'] = false;
        foreach($_POST as $key => $value){
            if(empty($_POST[$key])){
                $status[$key] = false;
                $errors[$key] = 'Обязательное поле!';
            }
        }
        if(empty($_FILES['lot-img']['name'])){
            $status['lot-img'] = false;
            $errors['lot-img'] = 'Обязательное поле!';
        }
}

//Если все поля заполнены и равны true, форма тоже равна true и создается новый lot на sql
if(check_array_by_condition($empty_status,false,"and") and
   check_array_by_condition($status,true,"and")){
        $status['form'] = true;
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
if($is_auth_status){
    $tempates_name = 'add.main.php';
    show_page($title_name,$tempates_name,$categorys,$is_auth,$user_name, $content_array = [
                                                                                        'errors' => $errors,
                                                                                        'categorys' => $categorys,
                                                                                        'lot_link' => $lot_link,
                                                                                        'status' => $status
                                                                                        ]);
}else{
    page_404($is_auth,$categorys,$user_name);}