<?php
function select_user_by_email($email,$sql_host){
    $check_mail =
    "SELECT users.*
     FROM users
     WHERE email = ?";
     $mail_query = prepared_query($check_mail,$sql_host,[$email])->get_result();
     return mysqli_fetch_assoc($mail_query);
}
function select_user_by_token($email,$auth_token,$sql_host){
    $select_user_by_token = 
   "SELECT users.*
    FROM users
    WHERE email = ? 
    AND auth_token = ?";
    $user_query = prepared_query($select_user_by_token,$sql_host,[$email,$auth_token])->get_result();
    return mysqli_fetch_assoc($user_query);
}
function select_lot_by_id($id,$sql_host){
    $select_lots = 
    "SELECT lots.id ,name,start_price,img_link,
    MAX(COALESCE(bids.price,lots.start_price)) AS price, 
    date_completion ,category,description, 
    MAX(COALESCE(bids.price,lots.start_price)) + step_rate AS min_bid

    FROM lots
    LEFT JOIN bids
    ON lots.id = bids.lot_id

    LEFT JOIN categories
    ON lots.category_id = categories.id

    WHERE lots.id = ?
    GROUP BY lots.id
    ORDER BY lots.date_create DESC;";
    $products_query = prepared_query($select_lots,$sql_host,[$id])->get_result();
    return mysqli_fetch_assoc($products_query);
}
function select_lots($sql_host){
    $select_lots = 
    "SELECT lots.id ,name,start_price,img_link,
    MAX(COALESCE(bids.price,lots.start_price)) AS price, 
    date_completion ,category

    FROM lots
    LEFT JOIN bids
    ON lots.id = bids.lot_id

    LEFT JOIN categories
    ON lots.category_id = categories.id

    WHERE lots.date_completion >= NOW()
    GROUP BY lots.id
    ORDER BY lots.date_create DESC;";
    return mysqli_fetch_all(mysqli_query($sql_host,$select_lots),MYSQLI_ASSOC);
}
function select_bids_by_id($id,$sql_host){
    $select_bids = 
    "SELECT bids.date_create, bids.price ,users.name
    FROM bids
    JOIN users
    ON users.id = bids.user_id
    WHERE bids.lot_id = ?
    ORDER BY bids.date_create DESC;";
    $bids_query = prepared_query($select_bids,$sql_host,[$id])->get_result();
    return mysqli_fetch_all($bids_query,MYSQLI_ASSOC);;
}
function insert_new_lot($sql_host){
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
    prepared_query($insert_add_pos,$sql_host,[
                        date("Y-m-d H:i:s"),
                        $_POST['lot-name'],
                        $_POST['message'],
                        $_SESSION['user']['id'],
                        0,
                        $_POST['category'],
                        'None',
                        $_POST['lot-rate'],
                        $_POST['lot-date'],
                        $_POST['lot-step']]);   
}
function insert_new_user($sql_host){
    $insert_new_user = 
    "INSERT INTO users(date_create,email,name,password,сontact)  
     VALUES (?,?,?,?,?)";
     prepared_query($insert_new_user,$sql_host,[date("Y-m-d H:i:s"),$_POST['email'],$_POST['name'],password_hash($_POST['password'],PASSWORD_DEFAULT),$_POST['message']]);
}
function update_file_link($id,$file_url,$sql_host){
    $update_file_link=
    "UPDATE lots
    SET img_link = ?
    WHERE id = ?";
    prepared_query($update_file_link,$sql_host,[$file_url,$id]);
}
function update_token($email,$sql_host,$len_token = 30){
    $auth_token = bin2hex(random_bytes($len_token));
    $update_token = 
    "UPDATE users
     SET auth_token = ?
     WHERE email = ?";
     prepared_query($update_token,$sql_host,[$auth_token,$email]);
     setcookie("login",$email,strtotime('+1 years'),"/");
     setcookie("auth_token",$auth_token,strtotime('+1 years'),"/");
}