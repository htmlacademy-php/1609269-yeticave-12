<?php
include(__DIR__.'/bootstrap.php');
if(empty($_GET['id'])){
    page_404($is_auth,$categorys,$_SESSION['user_name']);
}else{
    $id = $_GET['id'];
}

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
$select_bids = 
    "SELECT bids.date_create, bids.price ,users.name
    FROM bids
    JOIN users
    ON users.id = bids.user_id
    WHERE bids.lot_id = ?
    ORDER BY bids.date_create DESC;";

$products_query = prepared_query($select_lots,$con,[$id])->get_result();
$bids_query = prepared_query($select_bids,$con,[$id])->get_result();

$products = mysqli_fetch_assoc($products_query);
$bids =  mysqli_fetch_all($bids_query,MYSQLI_ASSOC);
if(!$products){
    page_404($is_auth,$categorys,$user_name);
}else{
    show_page("lot.html.php",$products['name'],['products' =>$products,'bids' => $bids],$categorys,$is_auth,$user_name);
}