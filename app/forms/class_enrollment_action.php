<?
// Tanya Trujillo
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../util.php';
require_once __DIR__ . '/../models/class_enrollment.php';

use App\Class_Enrollment;

$ce_num = $_POST['ce_num'];
$action = $_POST['action'];
//$user = App\User::find($_POST['UIN']);
$class_enrollment = App\Class_Enrollment::find($ce_num);

switch ($action) {
  case 'update':
    $class_enrollment->UIN = $_POST['UIN'];
    $class_enrollment->class_id = $_POST['class_id'];
    $class_enrollment->status = $_POST['status'];
    $class_enrollment->year = $_POST['year'];
    $class_enrollment->semester = App\Semester::from($_POST['semester']);
    $class_enrollment->save();
    App\redirect("/pages/student_program_tracking.php");

    break;
  case 'delete':
    $class_enrollment->delete();
    App\redirect("/pages/student_program_tracking.php");
    break;
  default:
    App\redirect("/pages/student_program_tracking.php");
    break;
}