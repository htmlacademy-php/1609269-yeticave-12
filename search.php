<?php
include(__DIR__.'/bootstrap.php');
if(empty($_GET['search'])){
    header("Location: /index.php");
    die();
}
$page = (isset($_GET['page']) and $_GET['page'] > 0) ? $_GET['page']: 1;
$limit = (isset($_GET['limit']) and $_GET['limit'] > 0) ? $_GET['limit']: 6;
$search = str_replace(["+","-","<",">","(",")","~","*",'"'],"",$_GET['search']);
$search = preg_replace('/([^ ]+)/', '+$1*', $search);
$search_query = 
"SELECT COUNT(*) as count
 FROM lots
 WHERE MATCH(lots.name, lots.description) AGAINST(? IN BOOLEAN MODE)" ;
$founding_lots = prepared_query($search_query,$con,[$search])->get_result();
$count_lots = mysqli_fetch_assoc($founding_lots);
$count_page = ceil($count_lots['count']/$limit);
$search_with_limit =
"SELECT lots.*, COALESCE((SELECT max(price) FROM bids WHERE lot_id = lots.id),lots.start_price) AS price
 FROM lots
 WHERE MATCH(lots.name, lots.description) AGAINST(? IN BOOLEAN MODE)
 LIMIT ? 
 OFFSET ?";
$founding_lots_limit = prepared_query($search_with_limit,$con,[$search,$limit,($page-1)*$limit])->get_result();
$lots = mysqli_fetch_all($founding_lots_limit,MYSQLI_ASSOC);
if($page > $count_page and $count_page > 0){
    page_404($categorys);
} 
$http = function($page) use ($limit){
    return http_build_query(['page' => $page,'limit' => $limit,'search' => $_GET['search']]);
};
show_page("search.html.php","Результат поиска",['lots' =>$lots,
                                                'http' =>$http,
                                                'count_page' => $count_page,
                                                'page' => $page,
                                                'limit' => $limit],$categorys);