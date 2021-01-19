<?php
require __DIR__."/bootstrap.php";
$find_winners_query =
"SELECT bids.user_id,users.name,lot_id,lots.name AS lot_name,email
 FROM bids

 LEFT JOIN lots
 ON bids.lot_id = lots.id

 LEFT JOIN users
 ON bids.user_id = users.id

 WHERE date_completion <= NOW() AND winner_id IS NULL
 GROUP BY lot_id";
$find_winners_prep = prepared_query($find_winners_query,$con)->get_result();
$winners = mysqli_fetch_all($find_winners_prep,MYSQLI_ASSOC)[0];

ob_start();
extract([$winners]);
require __DIR__."/email.php";
$page = ob_get_clean();

$transport = (new Swift_SmtpTransport('phpdemo.ru', 25))
                                     ->setUsername('keks@phpdemo.ru')
                                     ->setPassword('htmlacademy');
// Формирование сообщения
$message = new Swift_Message("Выбор победителя!");
$message->setSubject('Ваша ставка победила');
$message->setFrom("keks@phpdemo.ru","Yeti Cave");
$message->setTo([$winners['email']]);
$message->setBody($page);

// Отправка сообщения
$mailer = new Swift_Mailer($transport);
$mailer->send($message);
#"UPDATE lots
# SET winner_id = COALESCE((
 #  SELECT user_id
  # FROM bids
  # WHERE lot_id = lots.id
  # ORDER BY price DESC
  # limit 1),user_id)
 #WHERE date_completion <= NOW() AND winner_id IS NULL";
#$stmt = $con -> prepare($sql_query);
#$stmt->execute();
