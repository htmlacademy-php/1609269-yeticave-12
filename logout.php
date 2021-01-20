<?php
include(__DIR__.'/bootstrap.php');
un_login(['auth_token','login'], ['user']);
header("Location: /index.php");
die();
