<?php
require __DIR__."/bootstrap.php";


$sql_query =
"UPDATE lots
 SET winner_id = COALESCE((
   SELECT user_id
   FROM bids
   WHERE lot_id = lots.id
   ORDER BY price DESC
   limit 1),user_id)
 WHERE date_completion <= NOW() AND winner_id IS NULL";
$stmt = $con -> prepare($sql_query);
$stmt->execute();

// Конфигурация траспорта
$transport = (new Swift_SmtpTransport('phpdemo.ru', 25))
                                     ->setUsername('keks@phpdemo.ru')
                                     ->setPassword('htmlacademy');
// Формирование сообщения
$message = new Swift_Message("Просмотры вашей гифки");
$message->setSubject('Ваша ставка победила');
$message->setFrom("keks@phpdemo.ru","Yeti Cave");
$email_content = require __DIR__."/email.php";
$message->setTo(["apgrayedd@mail.ru"]);
$message->setBody($email_content);

// Отправка сообщения
$mailer = new Swift_Mailer($transport);
$mailer->send($message);
