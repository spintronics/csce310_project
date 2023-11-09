<?php
// Kevin Brown
require_once __DIR__ . '/../util.php';
require_once __DIR__ . '/../models/user.php';


use App\User;

use function App\redirect;

try {
  $existingUser = User::findByEmail($_POST['email']);
  if ($existingUser) {
    redirect("/pages/login.php?error=Email already exists");
    exit;
  } else {
    throw new Exception("User not found");
  }
} catch (\Throwable $e) {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $user = new App\User();
  $user->email = $email;
  $user->password = $password;
  $user->create();

  $user = User::findByEmail($email);
  $user->saveSession();

  redirect("/pages/user.php");
}



exit;
