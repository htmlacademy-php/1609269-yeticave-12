<?php
include(__DIR__.'/bootstrap.php');
if(empty($_GET['search'])){
    header("Location: /index.php");
    die();
};
$_SESSION['search'] = $_GET['search'];
$search = explode(" ",$_GET['search']);
for($i = 0;$i < count($search);$i++){
    $search[$i] = $search[$i]."*";
}
$search_query = 
"SELECT *,category
 FROM lots

 JOIN categories
 ON lots.category_id = categories.id
 WHERE MATCH(lots.name, lots.description) AGAINST(? IN BOOLEAN MODE)".str_repeat(
 " AND MATCH(lots.name, lots.description) AGAINST(? IN BOOLEAN MODE)",count($search) - 1);

$founding_lots = prepared_query($search_query,$con,$search)->get_result();
$lots = mysqli_fetch_all($founding_lots,MYSQLI_ASSOC);
show_page("search.html.php","Результат поиска",['lots' =>$lots],$categorys);