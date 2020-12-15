<?php
include(__DIR__."/bootstrap.php");
//Проверка авторизации
if($is_auth == 1){
    //Создание переменных
    $title_name = "Добавление файл";
    $reuslt = [];
    $lot_link = 0;
    $error = '';
    $errors =  ['name' => "",
                'category' => '',
                'message' => '',
                'rate' => '',
                'step' => '',
                'date' => '',
                'file' => '',
                'form' => ''];
    $status =  ['name' => $name_status = true,
                'category' => $category_status = true,
                'message' => $message_status = true, 
                'rate' => $rate_status = true, 
                'step' => $step_status = true, 
                'date' => $date_status = true, 
                'file' => $file_status = true, 
                'form' => $form = true];
    //Проверка POST и FILES на элементы, если они пустые, то форма равнa true
    if(empty(array_filter(array_values($_POST))) and empty(array_filter(array_values($_FILES)))) {
            $status['form'] = true;
    }else{
            //Так как 1 и более полей заполнено, меняет переменным статус на false и начинает проверку
            for($i=0;$i++;count($status)){
                $status[$i] = false;
            }
            //Если поле "Наименование" заполнено - начинает его проверку 
            if(!empty($_POST["lot-name"])){
                $reuslt = isCorrectLength($_POST["lot-name"],5,20);  //проверка длины 
                if($reuslt ['status']){$status['name'] = true;}
                                else{$status['name'] = false;$errors['name'] = $reuslt['error'];}              
            }else{
                 $status['name'] = true;
            }
            //Если поле "Описание" заполнено - начинает его проверку                                                                                       
            if(!empty($_POST["message"])){
                $reuslt = isCorrectLength($_POST["message"],5,3000); //проверка длины 
                if($reuslt['status']){$status['message'] = true;}
                                else{$status['message'] = false;$errors['message'] = $reuslt['error'];}              
            }else{
                $status['message'] = true;
           }
            //Если поле "Категория" заполнено - начинает его проверку 
            if(!empty($_POST["category"])){
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
                $reuslt[0] = isCorrectLength($_POST['lot-rate'],1,9);    //проверка длины 
                $reuslt[1] = isInt($_POST['lot-rate']);                  //проверка типа
                $reuslt[2] = check_condition($_POST['lot-rate'],'>',0);  //проверка: подходит ли под условие
                if($reuslt[0]['status']){$status[0]['rate'] = true;}
                                    else{$status[0]['rate'] = false;
                                        $errors['rate'] = $errors['rate'].$reuslt[0]['error']."<br>";}
                if($reuslt[1]['status']){$status[1]['rate'] = true;}
                                    else{$status[1]['rate'] = false;
                                        $errors['rate'] = $errors['rate'].$reuslt[1]['error']."<br>";}
                if($reuslt[2]['status']){$status[2]['rate'] = true;}
                                    else{$status[2]['rate'] = false;
                                        $errors['rate'] = $errors['rate'].$reuslt[2]['error']."<br>";}
                $status['rate'] = ($status[0]['rate'] && $status[1]['rate'] && $status[2]['rate']) ? true: false;
            }else{
                $status['rate'] = true;
           }  
            //Если поле "Шаг ставки" заполнено - начинает его проверку 
            if(!empty($_POST["lot-step"])){
                $reuslt[0] = isCorrectLength($_POST['lot-step'],1,9);    //проверка длины 
                $reuslt[1] = isInt($_POST['lot-step']);                  //проверка типа
                $reuslt[2] = check_condition($_POST['lot-step'],'>',0);  //проверка: подходит ли под условие
                if($reuslt[0]['status']){$status[0]['step'] = true;}
                                    else{$status[0]['step'] = false;
                                        $errors['step'] = $errors['step'].$reuslt[0]['error']."<br>";}
                if($reuslt[1]['status']){$status[1]['step'] = true;}
                                    else{$status[1]['step'] = false;
                                        $errors['step'] = $errors['step'].$reuslt[1]['error']."<br>";}
                if($reuslt[2]['status']){$status[2]['step'] = true;}
                                    else{$status[2]['step'] = false;
                                        $errors['step'] = $errors['step'].$reuslt[2]['error']."<br>";}
                $status['step'] = ($status[0]['step'] && $status[1]['step'] && $status[2]['step']) ? true: false;
            }else{
                $status['step'] = true;
           }  
            //Если поле "Дата окончания торгов " заполнено - начинает его проверку 
            if(!empty($_POST["lot-date"])){
                $reuslt = isCorrectDate($date = $_POST['lot-date'],$condition = "+ 1 days");  //проверка даты: подходит ли под условие
                if($reuslt['status']){$status['date'] = true;}
                                else{$status['date'] = false;$errors['date'] = $reuslt['error'];}           
            }else{
                $status['date'] = true;
           }  
            //Если поле "Изображение" заполнено - начинает его проверку 
            if(!empty($_FILES["lot-img"]['name'])){                               
                $reuslt = isCorrectImg($_FILES["lot-img"],5,['jpeg','jpg','png']);            //проверка файла: подходит ли под условие
                if($reuslt['status']){$status['file'] = true;}
                                else{$status['file'] = false;$errors['file'] = $reuslt['error'];}      
            }else{
                $status['file'] = true;
           }
            //Если поле "Изображение" прошло проверку на true: перемещает файл в необходимый каталог
            if($status['file']){
                $file_name = $_FILES['lot-img']['name'];
                $file_path = __DIR__ . '/uploads/';
                $file_url = '/uploads/' . $file_name;
                move_uploaded_file($_FILES['lot-img']['tmp_name'], $file_path . $file_name);
            }
            //Если все поля заполнены и равны true, форма тоже равна true и создается новый lot на sql
            if( $status['name'] &&
                $status['category'] &&
                $status['message'] && 
                $status['rate'] && 
                $status['step'] && 
                $status['date'] &&
                $status['file'] &&
                !empty($_POST['lot-name']) &&
                !empty($_POST['category']) &&
                !empty($_POST['message']) && 
                !empty($_POST['lot-rate']) && 
                !empty($_POST['lot-step']) && 
                !empty($_POST['lot-date']) &&
                !empty($_FILES['lot-img'])){
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
        }else{
            $status['form'] = false;
        }
    }
    //Открытие страницы
    $tempates_name = 'add.main.php';
    show_page($title_name,$tempates_name,$categorys,$is_auth,$user_name, $content_array = [
                                                                                        'errors' => $errors,
                                                                                        'categorys' => $categorys,
                                                                                        'lot_link' => $lot_link,
                                                                                        'status' => $status
                                                                                        ]);
}else{
    page_404($is_auth,$categorys,$user_name);
}