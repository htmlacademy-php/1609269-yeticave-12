<?php
include(__DIR__.'/bootstrap.php');
if(empty($_GET['search']) and !isset($_SESSION['search'])){
    header("Location: /index.php");
    die();
};
$_GET['page'] = (isset($_GET['page']) and $_GET['page'] > 0) ? $_GET['page']: 1;
$_GET['search'] = ($_GET['search']) ?? $_SESSION['search'];
$_SESSION['search'] = $_GET['search'];
$limit = (!empty($_GET['limit']) and $_GET['limit'] > 0) ? $_GET['limit']: 6;


$search = explode(" ",$_GET['search']);
for($i = 0;$i < count($search);$i++){
    $search[$i] = $search[$i]."*";
}
$search_query = 
"SELECT *,category, MAX(COALESCE(bids.price,lots.start_price)) AS price
 FROM lots

 LEFT JOIN bids
 ON lots.id = bids.lot_id

 LEFT JOIN categories
 ON lots.category_id = categories.id

 WHERE MATCH(lots.name, lots.description) AGAINST(? IN BOOLEAN MODE)".str_repeat(
 " AND MATCH(lots.name, lots.description) AGAINST(? IN BOOLEAN MODE)",count($search) - 1).
 " GROUP BY lots.id";

$founding_lots = prepared_query($search_query,$con,$search)->get_result();
$lots = mysqli_fetch_all($founding_lots,MYSQLI_ASSOC);
$count_page = ceil(count($lots)/$limit);

if($_GET['page'] > $count_page){
    page_404($categorys);
}

$search_with_limit = $search_query." LIMIT ? OFFSET ?";
$founding_lots_limit = prepared_query($search_with_limit,$con,array_merge($search,[$limit,($_GET['page']-1)*$limit]))->get_result();
$lots = mysqli_fetch_all($founding_lots_limit,MYSQLI_ASSOC);

show_page("search.html.php","Результат поиска",['lots' =>$lots,'count_page' => $count_page],$categorys);