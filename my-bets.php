<?php
include __DIR__."/bootstrap.php";
if(!isset($_SESSION['user']['name'])){
    page_403($categorys,'Для доступа к данному ресурсу необходимо <a href ="login.php">авторизоваться</a>');
}
$select_bids = 
"SELECT bids.id,bids.date_create,bids.user_id,lots.id AS lot_id,lots.name,img_link,bids.price,date_completion,
lots.category_id,users.сontact,users.name AS user,lots.winner_id,
IF(lots.date_completion > NOW(),1,0) AS lot_status,
(SELECT MAX(price) FROM bids WHERE lot_id = lots.id) as max_price

FROM bids

LEFT JOIN users
ON bids.user_id = users.id

LEFT JOIN lots
ON bids.lot_id = lots.id

WHERE bids.user_id = ?
GROUP BY bids.id
ORDER BY lot_status DESC,bids.date_create DESC;";
$bids_prepared = prepared_query($select_bids,$con,[$_SESSION['user']['id']])->get_result();
$bids = mysqli_fetch_all($bids_prepared,MYSQLI_ASSOC);
show_page("my-bets.html.php","Мои ставки",['bids' => $bids],$categorys);