<?php
include(__DIR__.'/bootstrap.php');
$products = select_lots($con);
if(empty($_GET['search'])){
    header("Location: /index.php");
    die();
};
$search = explode(" ",$_GET['search']);

$search_query = 
"SELECT *,category
 FROM lots

 JOIN categories
 ON lots.category_id = categories.id
 WHERE lots.name LIKE ?".str_repeat(" AND lots.name LIKE ?",count($search) - 1);                                            
for($i = 0; $i < count($search);$i++){
    $search[$i] = "%".$search[$i]."%";
}
$founding_lots = prepared_query($search_query,$con,$search)->get_result();
$lots = mysqli_fetch_all($founding_lots,MYSQLI_ASSOC);
show_page("search.html.php","Результат поиска",['lots' =>$lots],$categorys);