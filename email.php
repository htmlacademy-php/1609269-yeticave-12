<h1>Поздравляем с победой</h1>
<p>Здравствуйте, <?=$winners['name']?></p>
<p>Ваша ставка для лота <a href="http://"<?=$_SERVER['HTTP_HOST']?>/lot.php?id=<?=$winners['lot_id']?>"><?=$winners['lot_name']?></a> победила.</p>
<p>Перейдите по ссылке <a href="http://<?=$_SERVER['HTTP_HOST']?>/my-bets.php">мои ставки</a>,
чтобы связаться с автором объявления</p>
<small>Интернет Аукцион "YetiCave"</small>