<?
// Tanya Trujillo
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../util.php';
require_once __DIR__ . '/../models/intern_app.php';

use App\Intern_App;

$intern_app = new App\Intern_App();

$ia_num = $_POST['ia_num'];
$action = $_POST['action'];
//$user = App\User::find($_POST['UIN']);
$intern_app = App\Intern_App::find($ia_num);

switch ($action) {
  case 'update':
    $intern_app->UIN = $_POST['UIN'];
    $intern_app->intern_id = $_POST['intern_id'];
    $intern_app->status = $_POST['status'];
    $intern_app->year = $_POST['year'];
    $intern_app->intern_name = $_POST['intern_name'];
    $intern_app->intern_description = $_POST['intern_description'];
    $intern_app->is_gov = $_POST['is_gov'];
    $intern_app->save();
    App\redirect("/pages/student_program_tracking.php");

    break;
  case 'delete':
    $intern_app->delete();
    App\redirect("/pages/student_program_tracking.php");
    break;
  default:
    App\redirect("/pages/student_program_tracking.php");
    break;
}