<?php
include(__DIR__.'/bootstrap.php');
if(empty($_GET['id'])){
    page_404($categorys);
}else{
    $id = $_GET['id'];
}
$bids = select_bids_by_id($id,$con);
$lot = select_lot_by_id($id,$con);
$error = "";
if($_SERVER['REQUEST_METHOD'] == "POST"){
    $error = ($lot['min_bid'] > $_POST['cost']) ? "Ваша ставка ниже минимальной!":false;
    if(!$error){
        $insert_bid =
       "INSERT INTO bids(date_create,price,lot_id,user_id)
        VALUES(?,?,?,?)";
        prepared_query($insert_bid,$con,[date("Y-m-d H:i:s"),$_POST['cost'],$_GET['id'],$_SESSION['user']['id']]);
        header("Location: /lot.php?id=".$id);
        die();
    }
}
if(!$lot){
    page_404($categorys);
}else{
    show_page("lot.html.php",$lot['name'],['lot' =>$lot,'bids' => $bids,'error' => $error],$categorys);
}