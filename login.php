<?php

require __DIR__ . "/vendor/autoload.php";

use Source\Models\UserModel;

$post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRIPPED);

if(!filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL)){
    echo "Email informado não é válido!";
}

$model = new UserModel();
$user = $model->findByEmail($post['email']);

if(!password_verify($post['password'], $user->password)){
    echo "A senha informada não é válida!";
    var_dump($user);
} else {
    header("Location: https://www.localhost/devbook/dashboard.html");
}




