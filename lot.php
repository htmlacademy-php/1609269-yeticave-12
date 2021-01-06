<?php
include(__DIR__.'/bootstrap.php');
if(empty($_GET['id'])){
    page_404($categorys);
}else{
    $id = $_GET['id'];
}
$bids = select_bids_by_id($id,$con);
$products = select_lots_by_id($id,$con);
if(!$products){
    page_404($categorys);
}else{
    show_page("lot.html.php",$products['name'],['products' =>$products,'bids' => $bids],$categorys);
}