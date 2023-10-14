<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php';


$email = $_POST['email'];
$password = $_POST['password'];
$user = new App\User($email, $password);
$user->save();
