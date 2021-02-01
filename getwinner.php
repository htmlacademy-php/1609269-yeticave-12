<?php
$winners = select_bids_by_date_and_winner($con);
update_winner($con);
foreach ($winners as $winner) {
    $page = include_template('email.php', ['winner' => $winner]);
    sending_message($mailer,"Yeti Cave",
                    [$winner['email']],
                    'Ваша ставка победила',
                    $page,"text/html");
}

