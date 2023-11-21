<?php
// Kevin Brown
require_once __DIR__ . '/../util.php';
require_once __DIR__ . '/../models/user.php';


use App\User;

use function App\redirect;

try {
  if (!isset($_POST['UIN']) || !isset($_POST['password'])) {
    redirect("/pages/login.php?error=Invalid UIN or password");
    exit;
  }
  $existingUser = User::findBy('UIN', $_POST['UIN']);
  if (!$existingUser) {
    redirect("/pages/login.php?error=Invalid UIN or password");
    exit;
  }
  if (!$existingUser->verifyPassword($_POST['password'])) {
    redirect("/pages/login.php?error=Invalid UIN or password");
    exit;
  }
  if (!$existingUser->active_account) {
    redirect("/pages/login.php?error=Account is inactive");
    exit;
  }
  $existingUser->saveSession();

  redirect("/pages/home.php");
} catch (\Throwable $e) {
  echo $e->getMessage();
  redirect("/pages/login.php?error=Invalid UIN or password");
}



exit;
