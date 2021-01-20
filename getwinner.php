<?php
$winners = select_bids_by_date_and_winner($con);
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
}
update_winner($con);