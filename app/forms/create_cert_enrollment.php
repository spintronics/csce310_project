<?
// Tanya Trujillo
require_once '../models/cert_enrollment.php';
require_once '../util.php';

use function App\redirect;
$cert_enrollment = new App\Cert_Enrollment();

if (isset($_POST['cert_id'])) {
    $cert_id = $_POST['cert_id'];
} else {
    $cert_id = 0;
}


//$is_gov = isset($_POST['is_gov']) ? 1 : 0;


$cert_enrollment->UIN = (int)$_POST['UIN'];
//$cert_enrollment->cert_id = (int)$_POST['cert_id'];
$cert_enrollment->year = (int)$_POST['year'];
$cert_enrollment->status = $_POST['status'];
$cert_enrollment->training_status = $_POST['training_status'];
$cert_enrollment->semester = App\CertSemester::from($_POST['semester']);
$cert_enrollment->cert_name = $_POST['cert_name'];
$cert_enrollment->cert_description = $_POST['cert_description'];
$cert_enrollment->level = $_POST['level'];
$cert_enrollment->program_num = (int)$_POST['program_num'];
//$cert_enrollment->program_name = $_POST['program_name'];

$cert_enrollment->create();

redirect("/pages/student_program_tracking.php");

exit;