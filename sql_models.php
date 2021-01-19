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
    MAX(COALESCE(bids.price,lots.start_price)) + step_rate AS min_bid,
    IF(lots.date_completion > NOW(),1,0) AS lot_status

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
    "SELECT lots.id ,lots.date_create,name,start_price,img_link,
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
function select_bids_by_id($id,$sql_host,$limit = 10){
    $select_bids = 
    "SELECT bids.date_create, bids.price ,users.name
    FROM bids
    JOIN users
    ON users.id = bids.user_id
    WHERE bids.lot_id = ?
    ORDER BY bids.date_create DESC
    limit ?;";
    $bids_query = prepared_query($select_bids,$sql_host,[$id,$limit])->get_result();
    return mysqli_fetch_all($bids_query,MYSQLI_ASSOC);;
}
function insert_new_lot($sql_host){
    $insert_add_pos=
    "INSERT INTO lots  (date_create,
                        name,
                        description,
                        user_id,
                        category_id,
                        img_link,
                        start_price,
                        date_completion,
                        step_rate)
    VALUES (?,?,?,?,?,?,?,?,?);";
    prepared_query($insert_add_pos,$sql_host,[
                        date("Y-m-d H:i:s"),
                        $_POST['lot-name'],
                        $_POST['message'],
                        $_SESSION['user']['id'],
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
function count_lots_by_search_query($sql_host,$search_query){
    $sql_query = 
    "SELECT COUNT(*) as count
     FROM lots
     WHERE MATCH(lots.name, lots.description) AGAINST(? IN BOOLEAN MODE)" ;
    $founding_lots = prepared_query($sql_query,$sql_host,[$search_query])->get_result();
    return mysqli_fetch_assoc($founding_lots)['count'];
}
function select_lots_by_search_query($sql_host,$search_query,$limit,$page){
    $search_with_limit =
    "SELECT lots.*, COALESCE((SELECT MAX(price) FROM bids WHERE lot_id = lots.id),lots.start_price) AS price
     FROM lots
     WHERE MATCH(lots.name, lots.description) AGAINST(? IN BOOLEAN MODE)
     LIMIT ? 
     OFFSET ?";
    $founding_lots_limit = prepared_query($search_with_limit,$sql_host,[$search_query,$limit,($page-1)*$limit])->get_result();
    return mysqli_fetch_all($founding_lots_limit,MYSQLI_ASSOC);
}
