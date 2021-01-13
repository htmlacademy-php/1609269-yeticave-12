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
"SELECT bids.id,bids.date_create,bids.user_id,lots.id AS lot_id,name,start_price,img_link,bids.price,date_completion,
lots.category_id,(SELECT users.сontact FROM users WHERE users.id = lots.user_id) AS contact

FROM bids
LEFT JOIN lots
ON bids.lot_id = lots.id

WHERE bids.user_id = ?
GROUP BY bids.id
ORDER BY bids.date_create DESC;";
$bids_prepared = prepared_query($select_bids,$con,[$_SESSION['user']['id']])->get_result();
$bids = mysqli_fetch_all($bids_prepared,MYSQLI_ASSOC);
show_page("my-bets.html.php","Мои ставки",['bids' => $bids],$categorys);