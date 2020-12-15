<?php
include(__DIR__."/bootstrap.php");

$reuslt = [];
$lot_link = 0;
$error = '';
$errors =  ['name' => "",
            'message' => '',
            'rate' => '',
            'step' => '',
            'date' => '',
            'file' => '',
            'form' => ''];
$status =  ['name' => $name_status = true,
            'message' => $message_status = true, 
            'rate' => $rate_status = true, 
            'step' => $step_status = true, 
            'date' => $date_status = true, 
            'file' => $file_status = true, 
            'form' => $form = true];
if(empty(array_filter(array_values($_POST)))) {
        $status['form'] = true;
}else{
        for($i=0;$i++;count($status)){
            $status[$i] = false;
        }

        if(!empty($_POST["lot-name"])){
            $reuslt = isCorrectLength($_POST["lot-name"],5,20);
            if($reuslt ['status']){$status['name'] = true;}
                              else{$status['name'] = false;$errors['name'] = $reuslt['error'];}              
        }                                                                                       
        if(!empty($_POST["message"])){
            $reuslt = isCorrectLength($_POST["message"],5,20);
            if($reuslt['status']){$status['message'] = true;}
                             else{$status['message'] = false;$errors['message'] = $reuslt['error'];}              
        }     

        if(!empty($_POST["lot-rate"])){
            $reuslt[0] = isCorrectLength($_POST['lot-rate'],1,9);
            $reuslt[1] = isInt($_POST['lot-rate']);
            $reuslt[2] = check_condition($_POST['lot-rate'],'>',0);
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
        }
        if(!empty($_POST["lot-step"])){
            $reuslt[0] = isCorrectLength($_POST['lot-step'],1,9);
            $reuslt[1] = isInt($_POST['lot-step']);
            $reuslt[2] = check_condition($_POST['lot-step'],'>',0);
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
        }
        if(!empty($_POST["lot-date"])){
            $reuslt = isCorrectDate($date = $_POST['lot-date'],$condition = "+ 1 days");
            if($reuslt['status']){$status['date'] = true;}
                             else{$status['date'] = false;$errors['date'] = $reuslt['error'];}           
        }
        if(!empty($_FILES["lot-img"])){
            $reuslt = isCorrectImg($_FILES["lot-img"],5,['jpeg','jpg','png']);
            if($reuslt['status']){$status['file'] = true;}
                             else{$status['file'] = false;$errors['file'] = $reuslt['error'];}      
        }
        if($status['file']){
            $file_name = $_FILES['lot-img']['name'];
            $file_path = __DIR__ . '/uploads/';
            $file_url = '/uploads/' . $file_name;
            move_uploaded_file($_FILES['lot-img']['tmp_name'], $file_path . $file_name);
        }
        if( $status['name'] && 
            $status['message'] && 
            $status['rate'] && 
            $status['step'] && 
            $status['date'] &&
            $status['file']){
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
}
$title_name = "Добавление файл";
$tempates_name = 'add.main.php';
show_page($title_name,$tempates_name,$categorys,$is_auth,$user_name, $content_array = [
                                                                                    'errors' => $errors,
                                                                                    'categorys' => $categorys,
                                                                                    'lot_link' => $lot_link,
                                                                                    'status' => $status
                                                                                    ]);
