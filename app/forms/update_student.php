<?
// Kevin Brown
require_once '../models/User.php';
require_once '../models/Student.php';
require_once '../util.php';

use App\User;

use function App\redirect;

$user = User::fromSession();

if (!$user) {
  App\redirect("/pages/login.php");
  exit;
}
$_POST['UIN'] = $user->UIN;
$student = App\Student::fromResult($_POST);
$student->save();

redirect("/pages/user.php");

exit;
