<?php
include(__DIR__.'/bootstrap.php');
if(empty($_GET['id'])){
    page_404($categorys);
}else{
    $id = $_GET['id'];
}
$bids = select_bids_by_id($id,$con);
$lot = select_lot_by_id($id,$con);
if(!$lot){
    page_404($categorys);
}else{
    show_page("lot.html.php",$lot['name'],['lot' =>$lot,'bids' => $bids],$categorys);
}