<?php
include __DIR__."/bootstrap.php";
$page = (isset($_GET['page']) and $_GET['page'] > 0) ? $_GET['page']: 1;
$limit = (isset($_GET['limit']) and $_GET['limit'] > 0) ? $_GET['limit']: 6;
$count_lots = count_lots_by_category_id($con, $_GET['id']);
$count_page = ceil($count_lots/$limit);
if ($page > $count_page and $count_page > 0) {
    page_404($categorys);
    exit();
}
$lots = select_lots_by_category_id($con, $_GET['id'], $limit, $page);
show_page('all-lots.html.php', 'Все лоты', ['lots' => $lots,
                                          'limit' => $limit,
                                          'page' => $page,
                                          'count_page' => $count_page], $categorys);
