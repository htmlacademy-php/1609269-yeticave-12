<?php
include __DIR__."/bootstrap.php";
if(!isset($_SESSION['user']['name'])){
    page_403($categorys,'Для доступа к данному ресурсу необходимо <a href ="login.php">авторизоваться</a>');
    exit();
}
$page = (isset($_GET['page']) and $_GET['page'] > 0) ? $_GET['page']: 1;
$limit = (isset($_GET['limit']) and $_GET['limit'] > 0) ? $_GET['limit']: 10;

$sql_query = 
"SELECT COUNT(*) as count
 FROM bids
 WHERE bids.user_id = ?";
$founding_bids = prepared_query($sql_query,$con,[$_SESSION['user']['id']])->get_result();
$count_bids =  mysqli_fetch_assoc($founding_bids)['count'];
$count_page = ceil($count_bids/$limit);
if($page > $count_page and $count_page > 0){
    page_404($categorys);
    exit();
} 

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
$bids_prepared = prepared_query($select_bids,$con,[$_SESSION['user']['id'],$limit,($page-1)*$limit])->get_result();
$bids = mysqli_fetch_all($bids_prepared,MYSQLI_ASSOC);
show_page("my-bets.html.php","Мои ставки",['bids' => $bids,
                                           'limit' => $limit,
                                           'page' => $page,
                                           'count_page' => $count_page],$categorys);