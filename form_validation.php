<?php
function check_input_category($category,$categorys,$input = INPUT_POST){
    $category = filter_input($input,$category);
    if(!$category){ 
        return "Обязательное поле";
    }
    if(!isset($categorys[$category])){ 
        return 'Неправильно выбрана категория'; 
    }
}
function check_input($field_info,$min,$max,$filter = FILTER_DEFAULT,$input = INPUT_POST){
    $value = filter_input($input,$field_info);
    $length = mb_strlen($value);
    if($value !== false and !$length){
        return "Обязательное поле!";
    }
    $value = filter_input($input,$field_info,$filter);
    if(!$value){
        return "Введите корректные данные!";
    }
    if($filter === FILTER_VALIDATE_INT){
        if($value<$min or $value>$max){ 
            return "Необходимо ввести целое число от $min до $max"; 
        }
    }
    else if(mb_strlen($value)<$min or mb_strlen($value)>$max){ 
        return "Необходимо ввести от $min до $max символов"; 
    }
}
function check_input_file($img,$mb_limit = 5, $extensions,$mime){
    if(empty($_FILES[$img]['name'])){
        return "Обязательное поле";
    }else{
        if($_FILES[$img]['size'] > 1024*1024*$mb_limit){
            return "Файл не должен превышать ".$mb_limit." мб";
        }else{        
            $ext = pathinfo(trim($_FILES[$img]['name']), PATHINFO_EXTENSION);
            if(!in_array($ext,$extensions)){
                return "Файл может иметь формат(ы): ".implode(",",$extensions).", а не ".$ext;
            }
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $_FILES[$img]['tmp_name']);
            if (!in_array($mime_type,$mime)){
                return "Файл может иметь тип(ы): ".implode(",",$mime).", а не ".$mime_type;
            }
        }
    }
}
function check_input_date($date,$min = null,$max = null,$input = INPUT_POST){
    $date = filter_input($input,$date);
    if(!$date){ return "Обязательное поле";}
    else{
        if(strtotime($date) === false){
            return "Некоректная дата";
        }
        $date = date("Y-m-d",strtotime($date));
        $date_array = explode('-',$date);

        if(checkdate($date_array[1],$date_array[2],$date_array[0]) == false){
            return "Несуществующая дата!";
        }
        $min_date = date("Y-m-d",strtotime("+$min days")); 
        $max_date = date("Y-m-d",strtotime("+$max days")); 
        if($min !== null and $date <= $min_date){
            return "Дата должна быть не меньше $min_date";
        }
        if($max !== null and $date >= $max_date){
            return "Дата должна быть не больше $max_date";
        }
    }
}
