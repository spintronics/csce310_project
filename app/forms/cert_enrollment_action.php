<?
// Tanya Trujillo
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../util.php';
require_once __DIR__ . '/../models/cert_enrollment.php';


use App\Cert_Enrollment;

$cert_enrollment = new App\Cert_Enrollment();

$cert_num = $_POST['cert_num'];
$action = $_POST['action'];
//$user = App\User::find($_POST['UIN']);
$cert_enrollment = App\Cert_Enrollment::find($cert_num);

switch ($action) {
  case 'update':
    $cert_enrollment->UIN = $_POST['UIN'];
    $cert_enrollment->status = $_POST['status'];
    $cert_enrollment->year = $_POST['year'];
    $cert_enrollment->semester = App\CertSemester::from($_POST['semester']);
    $cert_enrollment->cert_name = $_POST['cert_name'];
    $cert_enrollment->cert_description = $_POST['cert_description'];
    $cert_enrollment->level = $_POST['level'];
    $cert_enrollment->program_num = $_POST['program_num'];
    $cert_enrollment->program_name = $_POST['program_name'];
    
    $cert_enrollment->save();
    App\redirect("/pages/student_program_tracking.php");

    break;
  case 'delete':
    $cert_enrollment->delete();
    App\redirect("/pages/student_program_tracking.php");
    break;
  default:
    App\redirect("/pages/student_program_tracking.php");
    break;
}