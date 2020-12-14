<?php
include(__DIR__."/bootstrap.php");

$lot_link = true;
$errors =  ['name' => "",
            'message' => '',
            'rate' => '',
            'step' => '',
            'date' => '',
            'file' => '',
            'form' => ''];
$status =  [['name_error' => $name_status = true,
            'message#' => $message_status = true, 
            'rate' => $rate_status = true, 
            'step' => $step_status = true, 
            'date' => $date_status = true, 
            'file' => $file_status = true, 
            'form' => $form = true],
            [
                'name_eror'
            ]];
if(empty(array_filter(array_values($_POST)))) {
        $status['form'] = true;
}else{
        for($i=0;$i++;count($status[0][])){
            $status[$i] = false;
        }

        if(!empty($_POST["lot-name"])){if(isCorrectLength($_POST["lot-name"],5,20) != $error){$status['name'] = true;}
                                                                                         else{$errors['name'] = $error;
                                                                                              $status['name'] = false; }}
        print($status['name']);
        if(!empty($_POST["message"])){ $status['message']=(isCorrectLength($_POST["message"],5,3000)) ?: false;}
        if(!empty($_POST["lot-rate"])){$status['rate'] = (isInt($_POST['lot-rate']) and isCorrectLength($_POST["lot-rate"],1,9)and $_POST["lot-rate"]>0) ?: false;};
        if(!empty($_POST["lot-step"])){$status['step'] = (isInt($_POST['lot-step']) and isCorrectLength($_POST["lot-step"],1,9) and $_POST["lot-step"]>0) ?: false;};
        if(!empty($_POST["lot-date"])){$status['date'] = (isCorrectDate($date = $_POST['lot-date'],$condition = "+ 1 days")) ?: false;};
        if(!empty($_FILES["lot-img"])){$status['file'] = isCorrectImg($_FILES["lot-img"]);}
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