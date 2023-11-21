<?php
// Kevin Brown
require_once __DIR__ . '/../util.php';
require_once __DIR__ . '/../models/user.php';


use App\User;

use function App\redirect;

// try {
$existingUser = User::find($_POST['UIN']);
if ($existingUser) {
  redirect("/pages/register.php?error=UIN already exists");
  exit;
}
$user_count = User::count();
$user = new App\User();
if ($user_count == 0) {
  // First user is an admin
  $user->user_type = App\UserType::Admin;
} else {
  $user->user_type = App\UserType::Student;
}
$user->username = $_POST['username'];
$user->password = $_POST['password'];
$user->UIN = $_POST['UIN'];
$user->create();

$user = User::find($_POST['UIN']);
$user->saveSession();

redirect("/pages/user.php");
exit;
// } catch (\Throwable $e) {
//   redirect("/pages/register.php?error=" . $e->getMessage());
//   exit;
// }


exit;
