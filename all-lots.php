<?php 
include __DIR__."/bootstrap.php";
$page = (isset($_GET['page']) and $_GET['page'] > 0) ? $_GET['page']: 1;
$limit = (isset($_GET['limit']) and $_GET['limit'] > 0) ? $_GET['limit']: 6;

$sql_query = 
"SELECT COUNT(*) as count
 FROM lots
 WHERE lots.category_id = ?";
$founding_lots = prepared_query($sql_query,$con,[$_GET['id']])->get_result();
$count_lots =  mysqli_fetch_assoc($founding_lots)['count'];
$count_page = ceil($count_lots/$limit);
if($page > $count_page and $count_page > 0){
    page_404($categorys);
    exit();
} 

$sql_query=
"SELECT id,name,img_link,category_id,date_completion,
COALESCE((SELECT max(price) FROM bids WHERE bids.lot_id = lots.id),start_price) as price
FROM lots
WHERE lots.category_id = ? AND lots.date_completion >= NOW()
LIMIT ?
OFFSET ?";
$lots = prepared_query($sql_query,$con,[$_GET['id'],$limit,($page-1)*$limit])->get_result();
$lots = mysqli_fetch_all($lots,MYSQLI_ASSOC);
show_page('all-lots.html.php','Все лоты',['lots' => $lots,
                                          'limit' => $limit,
                                          'page' => $page,
                                          'count_page' => $count_page],$categorys);