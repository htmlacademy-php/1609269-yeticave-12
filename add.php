<?php
include(__DIR__."boostrap.php");

$title_name = "Добавление лота";

$content = include_template("add.main.php",[]);
$page = include_template("layout.php",['content' => $content,
                                        'categorys' => $categorys,
                                        'is_auth' => $is_auth,
                                        'title_name' => $title_name,
                                        'user_name' => $user_name]);
print($page);
}