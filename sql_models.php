<?php
function select_user_by_email($email, $sql_host)
{
    $check_mail =
    "SELECT users.*
     FROM users
     WHERE email = ?";
    $mail_query = prepared_query($check_mail, $sql_host, [$email])->get_result();
    return mysqli_fetch_assoc($mail_query);
}
function select_user_by_token($email, $auth_token, $sql_host)
{
    $select_user_by_token =
   "SELECT users.*
    FROM users
    WHERE email = ?
    AND auth_token = ?";
    $user_query = prepared_query($select_user_by_token, $sql_host, [$email,$auth_token])->get_result();
    return mysqli_fetch_assoc($user_query);
}
function select_lot_by_id($id, $sql_host)
{
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
    $products_query = prepared_query($select_lots, $sql_host, [$id])->get_result();
    return mysqli_fetch_assoc($products_query);
}
function select_lots($sql_host)
{
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
    return mysqli_fetch_all(mysqli_query($sql_host, $select_lots), MYSQLI_ASSOC);
}
function select_bids_by_id($id, $sql_host, $limit = 10)
{
    $select_bids =
    "SELECT bids.date_create, bids.price ,users.name
    FROM bids
    JOIN users
    ON users.id = bids.user_id
    WHERE bids.lot_id = ?
    ORDER BY bids.date_create DESC
    limit ?;";
    $bids_query = prepared_query($select_bids, $sql_host, [$id,$limit])->get_result();
    return mysqli_fetch_all($bids_query, MYSQLI_ASSOC);
    ;
}
function insert_new_lot($sql_host, $date, $lot_name, $comment, $user_id, $category, $rate, $date_end, $step)
{
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
    prepared_query($insert_add_pos, $sql_host, [
                        $date,
                        $lot_name,
                        $comment,
                        $user_id,
                        $category,
                        'None',
                        $rate,
                        $date_end,
                        $step]);
}
function insert_new_user($sql_host, $date, $email, $name, $password, $message)
{
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $insert_new_user =
    "INSERT INTO users(date_create,email,name,password,сontact)
     VALUES (?,?,?,?,?)";
    prepared_query($insert_new_user, $sql_host, [$date,$email,$name,$password_hash,$message]);
}
function insert_new_bid($sql_host, $date, $price, $lot_id, $user_id)
{
    $insert_bid =
   "INSERT INTO bids(date_create,price,lot_id,user_id)
    VALUES(?,?,?,?)";
    prepared_query($insert_bid, $sql_host, [$date,$price,$lot_id,$user_id]);
}
function update_file_link($id, $file_url, $sql_host)
{
    $update_file_link=
    "UPDATE lots
    SET img_link = ?
    WHERE id = ?";
    prepared_query($update_file_link, $sql_host, [$file_url,$id]);
}
function update_token($email, $sql_host, $len_token = 30)
{
    $auth_token = bin2hex(random_bytes($len_token));
    $update_token =
    "UPDATE users
     SET auth_token = ?
     WHERE email = ?";
    prepared_query($update_token, $sql_host, [$auth_token,$email]);
    setcookie("login", $email, strtotime('+1 years'), "/");
    setcookie("auth_token", $auth_token, strtotime('+1 years'), "/");
}
function update_winner($sql_host,$winner)
{
    $update_winner_query=
    'UPDATE lots
    SET winner_id = 1
    WHERE name IN (?'.str_repeat(", ?",count($winner)-1).');';
    prepared_query($update_winner_query, $sql_host,$winner);
}
function count_lots_by_search_query($sql_host, $search_query)
{
    $sql_query =
    "SELECT COUNT(*) as count
     FROM lots
     WHERE MATCH(lots.name, lots.description) AGAINST(? IN BOOLEAN MODE)" ;
    $founding_lots = prepared_query($sql_query, $sql_host, [$search_query])->get_result();
    return mysqli_fetch_assoc($founding_lots)['count'];
}
function select_lots_by_search_query($sql_host, $search_query, $limit, $page)
{
    $search_with_limit =
    "SELECT lots.*, COALESCE((SELECT MAX(price) FROM bids WHERE lot_id = lots.id),lots.start_price) AS price
     FROM lots
     WHERE MATCH(lots.name, lots.description) AGAINST(? IN BOOLEAN MODE)
     LIMIT ?
     OFFSET ?";
    $founding_lots_limit = prepared_query($search_with_limit, $sql_host, [$search_query,$limit,($page-1)*$limit])->get_result();
    return mysqli_fetch_all($founding_lots_limit, MYSQLI_ASSOC);
}
function count_bids_by_user_id($sql_host, $user_id)
{
    $sql_query =
   "SELECT COUNT(*) as count
    FROM bids
    WHERE bids.user_id = ?";
    $founding_bids = prepared_query($sql_query, $sql_host, [$user_id])->get_result();
    return mysqli_fetch_assoc($founding_bids)['count'];
}
function count_lots_by_category_id($sql_host, $category_id)
{
    $sql_query =
    "SELECT COUNT(*) as count
    FROM lots
    WHERE lots.category_id = ?";
    $founding_lots = prepared_query($sql_query, $sql_host, [$category_id])->get_result();
    return mysqli_fetch_assoc($founding_lots)['count'];
}
function select_lots_by_category_id($sql_host, $user_id, $limit, $page)
{
    $sql_query=
   "SELECT id,name,img_link,category_id,date_completion,
    COALESCE((SELECT max(price) FROM bids WHERE bids.lot_id = lots.id),start_price) as price
    FROM lots
    WHERE lots.category_id = ? AND lots.date_completion >= NOW()
    LIMIT ?
    OFFSET ?";
    $lots = prepared_query($sql_query, $sql_host, [$user_id,$limit,($page-1)*$limit])->get_result();
    return mysqli_fetch_all($lots, MYSQLI_ASSOC);
}
function select_bids_by_user_id($sql_host, $user_id, $limit, $page)
{
    $select_bids =
   "SELECT bids.id,bids.date_create,bids.user_id,lots.id AS lot_id,lots.name,img_link,bids.price,date_completion,
    lots.category_id,lots.winner_id,lots.user_id,
    IF(lots.date_completion > NOW(),1,0) AS lot_status,
    (SELECT MAX(price) FROM bids WHERE lot_id = lots.id) AS max_price,
    (SELECT MAX(price) FROM bids WHERE lot_id = lots.id AND bids.user_id = users.id) AS max_price_same_user,
    (SELECT сontact FROM users WHERE id = lots.user_id) AS contact

    FROM bids

    LEFT JOIN users
    ON bids.user_id= users.id

    LEFT JOIN lots
    ON bids.lot_id = lots.id

    WHERE bids.user_id = ?
    ORDER BY lot_status DESC,bids.date_create DESC
    limit ?
    OFFSET ?";
    $bids_prepared = prepared_query($select_bids, $sql_host, [$user_id,$limit,($page-1)*$limit])->get_result();
    return mysqli_fetch_all($bids_prepared, MYSQLI_ASSOC);
}
function select_bids_by_date_and_winner($sql_host)
{
    $find_winners_query =
    "SELECT bids.*,users.name,users.email,lots.name AS lot_name
    FROM bids

    LEFT JOIN lots
    ON bids.lot_id = lots.id

    LEFT JOIN users
    ON bids.user_id = users.id

    WHERE bids.price = (
        SELECT price
        FROM bids
        WHERE lot_id = lots.id
        ORDER BY price DESC
        limit 1)
    AND date_completion <= NOW() AND winner_id IS NULL";
    $find_winners_prep = prepared_query($find_winners_query, $sql_host)->get_result();
    return mysqli_fetch_all($find_winners_prep, MYSQLI_ASSOC);
}
