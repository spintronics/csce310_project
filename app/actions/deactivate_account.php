<?

use function App\redirect;

require_once __DIR__ . '/../util.php';
require_once __DIR__ . '/../models/user.php';

$user = App\User::fromSession();

if (!$user) {
  redirect("/pages/login.php");
  exit;
}

$user->active_account = false;
$user->save();
$user->clearSession();

redirect("/pages/login.php");
