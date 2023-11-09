<?
require_once '../models/User.php';
require_once '../util.php';

use App\User;
use App\Role;
use App\Race;
use App\Gender;

use function App\redirect;
use function App\postOr;

$id = (int)$_COOKIE['user_id'];

if (!$id) {
  redirect("/pages/login.php");
  exit;
}

// echo json_encode($_POST);

$user = new User();
$user->id = $id;
$user->email = postOr('email', null);
$user->password = postOr('password', null);
$user->first_name = postOr('first_name', null);
$user->last_name = postOr('last_name', null);
$user->hispanic_or_latino = isset($_POST['hispanic_or_latino']);
$user->us_citizen = isset($_POST['us_citizen']);
$user->date_of_birth = postOr('date_of_birth', null);
$user->phone = postOr('phone', null);
$user->role = Role::find($_POST['role_id']);
$user->race = Race::find($_POST['race_id']);
$user->gender = Gender::find($_POST['gender_id']);

try {
  $user->save();
  redirect("/pages/user.php");
} catch (\Exception $e) {
  echo $e->getMessage();
  redirect("/pages/user.php?error=" . htmlspecialchars($e->getMessage()));
}
