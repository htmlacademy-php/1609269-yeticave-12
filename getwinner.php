<?php
$find_winners_query =
"SELECT bids.*,users.name,users.email,lots.name AS lot_name
 FROM bids

 LEFT JOIN lots
 ON bids.lot_id = lots.id

 LEFT JOIN users
 ON bids.user_id = users.id

 WHERE bids.price = (
    SELECT price
    FROM bids
    WHERE lot_id = lots.id
    ORDER BY price DESC
    limit 1)
AND date_completion <= NOW() AND winner_id IS NULL";
$find_winners_prep = prepared_query($find_winners_query,$con)->get_result();
$winners = mysqli_fetch_all($find_winners_prep,MYSQLI_ASSOC);

foreach($winners as $winner){
    $page = include_template('email.php',['winner' => $winner]);
    $transport = (new Swift_SmtpTransport('phpdemo.ru', 25))
                                        ->setUsername('keks@phpdemo.ru')
                                        ->setPassword('htmlacademy');
    $message = new Swift_Message("Выбор победителя!");
    $message->setSubject('Ваша ставка победила');
    $message->setFrom("keks@phpdemo.ru","Yeti Cave");
    $message->setTo([$winner['email']]);
    $message->setBody($page,"text/html");

    $mailer = new Swift_Mailer($transport);
    $mailer->send($message);

#   $update_winner_query=
#   "UPDATE lots
#    SET winner_id = ?
#    WHERE lots.id = ?";
#    prepared_query($update_winner_query,$con,[$winner['user_id'],$winner['lot_id']]);
}
$update_winner_query=
"UPDATE lots 
 SET winner_id = COALESCE((
    SELECT user_id
    FROM bids
    WHERE lot_id = lots.id
    ORDER BY price DESC
    limit 1),user_id)
 WHERE date_completion <= NOW() AND winner_id IS NULL";
prepared_query($update_winner_query,$con);