<?php

require_once __DIR__ . '/../util.php';
require_once __DIR__ . '/../models/user.php';

use App\User;


$email = $_POST['email'];
$password = $_POST['password'];
$user = new App\User($email, $password);
$user->save();

header("Location: /pages/home.php");

exit;
