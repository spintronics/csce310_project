<?php
// Kevin Brown
require_once __DIR__ . '/../util.php';
require_once __DIR__ . '/../models/user.php';


use App\User;

use function App\redirect;

try {
  $existingUser = User::findByEmail($_POST['email']);
  if (!$existingUser) {
    redirect("/pages/login.php?error=Invalid email or password");
    exit;
  }
  $existingUser->saveSession();

  redirect("/pages/home.php");
} catch (Exception $e) {
  echo $e->getMessage();
  redirect("/pages/login.php?error=Invalid email or password");
}



exit;
