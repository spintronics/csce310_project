<?
// Kevin Brown
require_once '../models/User.php';
require_once '../util.php';

use App\User;
use App\UserType;

use function App\redirect;
use function App\postOr;

$UIN = (int)$_COOKIE['user_uin'];

if (!$UIN) {
  redirect("/pages/login.php");
  exit;
}

// echo json_encode($_POST);
$_POST['UIN'] = $UIN;
if (!isset($_POST['user_type'])) {
  $_POST['user_type'] = (string) UserType::Student->value;
}
$_POST['active_account'] = true;
$user = User::fromResult($_POST);
$user->update();
redirect("/pages/user.php");

// try {
// } catch (\Exception $e) {
//   echo $e->getMessage();
//   redirect("/pages/user.php?error=" . htmlspecialchars($e->getMessage()));
// }
