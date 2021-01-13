<?php
include __DIR__."/bootstrap.php";
function date_end($date){
    if(date("Y-m-d h:i:s") > date("Y-m-d h:i:s",strtotime($date))){
        return false;
    }else{
        return true;
    }
}
function rate_win(){

}
$select_bids = 
"SELECT bids.id,bids.date_create,bids.user_id,lots.id AS lot_id,lots.name,start_price,img_link,bids.price,date_completion,
lots.category_id,users.сontact,users.name as user,
IF(lots.date_completion > NOW(),1,0) AS lot_status

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
print_r($bids[0]);
show_page("my-bets.html.php","Мои ставки",['bids' => $bids],$categorys);