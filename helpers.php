<?php
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form (int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */

function price_format($price){
    return number_format(ceil($price),0,'',' ') . ' ₽'; 
}

function include_template($name, array $data = []) {
    $name = __DIR__ .'/templates/' . $name;
    $result = '';

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

function diff_time($time){
    $hours = floor((strtotime($time) - time())/3600);
    $min = floor(((strtotime($time) - time())/3600 - $hours)*60);
    $hours = str_pad($hours,2,"0",STR_PAD_LEFT);
    $min = str_pad($min,2,'0',STR_PAD_LEFT);
    return [$hours,$min,];
//    return $diff = date_interval_format(date_diff(date_create('now'),date_create($time)),"%dд. %h:%i:%s");
}

//обработка запроса
function prepared_query($sql_query,$msqli,$passed_variables=[],$types_variables =""){
    $types = $types_variables ?: str_repeat("s",count($passed_variables));
    $stmt = $msqli->prepare($sql_query);
    $stmt->bind_param($types, ...$passed_variables);
    $stmt->execute();
    return $stmt;
}

//показ ошибки 404
function page_404($is_auth,$categorys,$user_name){
    http_response_code(404);
    $title_name = 'Файл не найден';
    $content = include_template("404.php",[]);
    $page = include_template("layout.php",['content' => $content,
                                        'is_auth' => $is_auth,
                                        'categorys' => $categorys,
                                        'title_name' => $title_name,
                                        'user_name' => $user_name]);
    print($page);
}

//показ страницы
function show_page($tempates_name,$title_name,$content_array = [],$categorys,$is_auth, $user_name){
    $content = include_template($tempates_name,array_merge(['categorys' => $categorys,'is_auth' => $is_auth],$content_array));
    $page = include_template("layout.php",[ 'content' => $content,
                                            'categorys' => $categorys,
                                            'is_auth' => $is_auth,
                                            'title_name' => $title_name,
                                            'user_name' => $user_name]);
    print($page);
}

//Проверка даты 
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

function e($output){
    return htmlspecialchars($output,ENT_QUOTES);
}

//Проверка файла
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

function move_file($file_name,$fime_tmp,$folder){
    $file_path = __DIR__ . '/'.$folder.'/';
    move_uploaded_file($fime_tmp, $file_path . $file_name);
}

function check_input($field_info,$min,$max,$filter = FILTER_DEFAULT,$input = INPUT_POST){
    $value = filter_input($input,$field_info);
    $length = mb_strlen($value);
    if($value !== false and !$length){
        return "Обязательное поле!";
    }
    $value = filter_input($input,$field_info,$filter);
    if($filter === FILTER_VALIDATE_INT){
        if($value === false or $value<$min or $value>$max){ 
            return "Необходимо ввести целое число от $min до $max"; 
        }
    }
    else if($value === false or mb_strlen($value)<$min or mb_strlen($value)>$max){ 
        return "Необходимо ввести от $min до $max символов"; 
    }
}
function check_input_category($category,$categorys,$input = INPUT_POST){
    $category = filter_input($input,$category);
    if(!$category){ 
        return "Обязательное поле";
    }
    if(!isset($categorys[$category])){ 
        return 'Неправильно выбрана категория'; 
    }
}
function select_user_by_email($email,$sql_host){
    $check_mail =
    "SELECT email
     FROM users
     WHERE email = ?";
     $mail_query = prepared_query($check_mail,$sql_host,[$email])->get_result();
     return mysqli_fetch_assoc($mail_query);
}
function un_login($link,$user_name){
    unset($_SESSION[$user_name]);
    header("Location: ".$link);
}