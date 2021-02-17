<?php
$winners = select_bids_by_date_and_winner($con);
if($winners){
    $lot_winners = array_column($winners,"lot_id");
    update_winner($con,$lot_winners);
    $transport = (new Swift_SmtpTransport($mailer["host"], $mailer["port"]))
    ->setUsername($mailer['username'])
    ->setPassword($mailer['password']);
    foreach ($winners as $winner) {
        $page = include_template('email.php', ['winner' => $winner]);
        sending_message($transport,
                        $mailer['username'],"Yeti Cave",
                        [$winner['email']],
                        'Ваша ставка победила',
                        $page,"text/html");
    }
}

