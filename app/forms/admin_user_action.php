<?
// Kevin Brown
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../util.php';

$action = $_POST['action'];
$user = App\User::find($_POST['UIN']);

switch ($action) {
  case 'deactivate':


    $user->active_account = false;
    $user->save();

    App\redirect("/pages/admin_users.php");
    break;
  case 'update':
    $user->first_name = $_POST['first_name'];
    $user->m_initial = $_POST['m_initial'];
    $user->last_name = $_POST['last_name'];
    $user->username = $_POST['username'];
    $user->password = $_POST['password'];
    $user->user_type = App\UserType::from($_POST['user_type']);
    $user->email = $_POST['email'];
    $user->discord_name = $_POST['discord_name'];
    $user->active_account = App\postOr('active_account', false);
    $user->save();
    App\redirect("/pages/admin_users.php");

    break;
  case 'activate':
    $user->active_account = true;
    $user->save();

    App\redirect("/pages/admin_users.php");
    break;
  case 'delete':
    $user->delete();
    App\redirect("/pages/admin_users.php");
    break;
  default:
    App\redirect("/pages/admin_users.php");
    break;
}
