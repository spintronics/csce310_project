<?
// Kevin Brown
use function App\redirect;

require_once __DIR__ . '/../util.php';
require_once __DIR__ . '/../models/user.php';

$user = App\User::fromSession();

// only admins can create random users
if (!$user || !$user->isAdmin()) {
  redirect("/pages/login.php");
  exit;
}

// create 100 random users
for ($i = 0; $i < 100; $i++) {
  App\User::createRandom();
}

// redirect back to admin users page

redirect("/pages/admin_users.php");
