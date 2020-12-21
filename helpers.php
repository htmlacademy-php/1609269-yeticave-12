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
function replace_in_query($sql_query,$msqli,$passed_variables=[],$types_variables =""){
    $types = $types_variables ?: str_repeat("s",count($passed_variables));
    $stmt = $msqli->prepare($sql_query);
    $stmt->bind_param($types, ...$passed_variables);
    $stmt->execute();
    return $sql_query = $stmt->get_result();
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
function show_page($title_name,$tempates_name,$categorys,$is_auth,$user_name,$content_array = []){

    $title_name = $title_name;

    $content = include_template($tempates_name,$content_array);
    $page = include_template("layout.php",['content' => $content,
                                            'categorys' => $categorys,
                                            'is_auth' => $is_auth,
                                            'title_name' => $title_name,
                                            'user_name' => $user_name]);
    print($page);
}

//Проверка даты 
function isCorrectDate($date,$date_type = "y-m-d",$separator = "-",$condition = "+ 1 days"){
    $date_array = explode($separator,$date); 
    if(checkdate($date_array[1],$date_array[2],$date_array[0]) == false){
        return $date." имеет неверный формат даты";
    }
    $tomorrow = date($date_type,strtotime($condition)); 
    $date_by_user = date($date_type,strtotime($date));
    if($date_by_user<$tomorrow){
        return "Дата должна подходить под условие ".$condition;
    }else{
        return false;
    }
}

//сохранить значения полей формы после валидации
function getPostVal($name) {
    return $_POST[$name] ?? "";
} 

//Проверка файла
function isCorrectImg($img,$mb_limit = 5, $expansions = ['jpeg','jpg','png']){
    if(empty($img['name'])){
        return "Файл не найден";
    }else{
        if($img['size'] > 1048576*$mb_limit){
            return "Файл не должен превышать ".$mb_limit." мб";
        }else{        
            $type_file = pathinfo(trim(strip_tags($img['name'])), PATHINFO_EXTENSION);
            if(in_array($type_file,$expansions) == false){
                return "Файл может иметь формат(ы): ".implode(",",$expansions).", а не ".$type_file;
            }else{
                return false;
            }
        }
    }
}

function move_file($file_name,$fime_tmp,$folder){
    $file_path = __DIR__ . '/'.$folder.'/';
    move_uploaded_file($fime_tmp, $file_path . $file_name);
}

function check_no_empty_post_and_files($array_keys_post =[],$array_keys_files=[]){
    $answer = true;
    $i = 0;
    while($i < (count($array_keys_post ) - 1)){
        if(in_array($array_keys_post [$i],array_keys($_POST))){
            if(empty($_POST[$array_keys_post[$i]])){
                $answer = false;
            }
        }else{
            $answer = false;}
        $i++;
    }
    while($i < (count($array_keys_files))){
        if(in_array($array_keys_files[$i],array_keys($_FILES))){
            if(isset($_FIELS[($array_keys_files)][$i]['name'])){
                $answer = false;
            }
        }else{
            $answer = false;}
        $i++;
    }
    return $answer;
}

function check_field($field_info,$field_type,$min = 1,$max = 20,$input = INPUT_POST){
    if($field_type == 'str'){
        $string = filter_input($input,$field_info);
        $len = strlen($string);
        if($len<$min or $len >$max){ return "Необходимо ввести от $min до $max символов";}
    }
    if($field_type == 'int'){
        $int = filter_input($input,$field_info,FILTER_VALIDATE_INT);
        if($int<$min or $int >$max){ return "Необходимо ввести чило от $min до $max";}
    }
}