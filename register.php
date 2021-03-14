<?php

require __DIR__ . "/vendor/autoload.php";

$post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRIPPED);
$technology = implode( ",", $post['technology'] );

$model = new \Source\Models\UserModel();

$user = $model->bootstrap(
    filter_input(INPUT_POST, "first_name", FILTER_SANITIZE_STRIPPED),
    filter_input(INPUT_POST, "last_name", FILTER_SANITIZE_STRIPPED),
    filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL),
    $post['performace'],
    $post['level'],
    $technology,
    filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRIPPED),
);



