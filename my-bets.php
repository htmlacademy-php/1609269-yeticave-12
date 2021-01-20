<?php
include __DIR__."/bootstrap.php";
if(!isset($_SESSION['user']['name'])){
    page_403($categorys,'Для доступа к данному ресурсу необходимо <a href ="login.php">авторизоваться</a>');
    exit();
}
$page = (isset($_GET['page']) and $_GET['page'] > 0) ? $_GET['page']: 1;
$limit = (isset($_GET['limit']) and $_GET['limit'] > 0) ? $_GET['limit']: 10;
$count_bids = count_bids_by_user_id($con,$_SESSION['user']['id']);
$count_page = ceil($count_bids/$limit);
if($page > $count_page and $count_page > 0){
    page_404($categorys);
    exit();
} 
$bids = select_bids_by_user_id($con,$_SESSION['user']['id'],$limit,$page);
show_page("my-bets.html.php","Мои ставки",['bids' => $bids,
                                           'limit' => $limit,
                                           'page' => $page,
                                           'count_page' => $count_page],$categorys);