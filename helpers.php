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

//Проверка длины 
function isCorrectLength($string, $min, $max) {
    $len = strlen($string);
    if ($len < $min or $len > $max) {
        $error = "Поле должно быть от ".$min." до ".$max." символов!";
        return ['status' => false, 'error' => $error];
    }else{
        return ['status' => true];
    }
}

//Проверка на int
function isInt($num){
    if(!is_numeric($num)){
        return ['status' => false, 'error' => "Поле должно содержать цифры!"]; 
    }else{
        return ['status' => true];
    }
}
//Проверка даты 
function isCorrectDate($date,$date_type = "d-m-y",$separator = "-",$condition = "+ 1 days"){
    $date_array = explode($separator,$date); 
    if(checkdate($date_array[1],$date_array[2],$date_array[0]) == false){
        return ['status' => false, 'error' => $date." имеет неверный формат даты"];
    }
    $tomorrow = date($date_type,strtotime($condition)); 
    $date_by_user = date($date_type,strtotime($date));
    if($date_by_user<$tomorrow){
        return ['status' => false, 'error' => "Дата должна подходить под условие ".$condition];
    }else{
        return ['status' => true];
    }
}

//сохранить значения полей формы после валидации
function getPostVal($name) {
    return $_POST[$name] ?? "";
}

//Проверка файла
function isCorrectImg($img,$mb_limit = 5, $expansions = ['jpeg','jpg','png']){
    if(empty($img['name'])){
        return ['status' => false, 'error' => "Файл не найден"];
    }else{
        if($img['size'] > 1048576*100*$mb_limit){
            return ['status' => false, 'error' => $img['name']." не должен превышать ".$mb_limit." мб"];
        }else{        
            $type_file = pathinfo(trim(strip_tags($img['name'])), PATHINFO_EXTENSION);
            if(in_array($type_file,$expansions) == false){
                return ['status' => false, 'error' => "Файл может иметь формат(ы): ".implode(",",$expansions).", а не ".$type_file];
            }else{
                return ['status' => true];
            }
        }
    }
}

function check_array_for_the_same($array = [],$tag,$value,$num){
    $the_same = true;
    for($i = 0; $i++;$i == $num){
        print($i.$array[$i][$tag]);
        if($array[$i] != $value){
            $the_same = false;
            break;
        } 
    }
    return $the_same;
}

function check_condition($item_to_compare,$condition = '=',$compare_with = 0){
    if(num_cond($item_to_compare,$condition,$compare_with)){
        return['status' => true];
    }else{
        return['status' => false,'error' => "Поле должно быть больше 0!"];
    }
}

function num_cond ($var1, $op, $var2) {

    switch ($op) {
        case "=":  return $var1 == $var2;
        case "!=": return $var1 != $var2;
        case ">=": return $var1 >= $var2;
        case "<=": return $var1 <= $var2;
        case ">":  return $var1 >  $var2;
        case "<":  return $var1 <  $var2;
    default:       return true;
    }   
}