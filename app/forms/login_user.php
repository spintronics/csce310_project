<?php
// Kevin Brown
require_once __DIR__ . '/../util.php';
require_once __DIR__ . '/../models/user.php';


use App\User;

use function App\redirect;

try {
  if (!isset($_POST['email']) || !isset($_POST['password'])) {
    redirect("/pages/login.php?error=Invalid email or password");
    exit;
  }
  $existingUser = User::findByEmail($_POST['email']);
  if (!$existingUser) {
    redirect("/pages/login.php?error=Invalid email or password");
    exit;
  }
  if (!$existingUser->verifyPassword($_POST['password'])) {
    redirect("/pages/login.php?error=Invalid email or password");
    exit;
  }
  $existingUser->saveSession();

  redirect("/pages/home.php");
} catch (\Throwable $e) {
  echo $e->getMessage();
  redirect("/pages/login.php?error=Invalid email or password");
}



exit;
