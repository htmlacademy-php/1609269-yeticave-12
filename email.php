<?php
function message($user_name,$lot_link,$lot_name,$link_lot_page){
    return 
    '<h1>Поздравляем с победой</h1>
    <p>Здравствуйте, '.$user_name.' </p>
    <p>Ваша ставка для лота <a href="'.$lot_link.'">'.$lot_name.'</a> победила.</p>
    <p>Перейдите по ссылке <a href="'.$link_lot_page.'">мои ставки</a>,
    чтобы связаться с автором объявления</p>
    <small>Интернет Аукцион "YetiCave"</small>';
}