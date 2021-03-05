<?php
$winners = select_bids_by_date_and_winner($con);
if($winners){
    update_winner($con,$winners);
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

