<?
// Tanya
require_once '../models/class_enrollment.php';
require_once '../util.php';

use function App\redirect;
$class_enrollment = new App\Class_Enrollment();

$class_enrollment->UIN = (int)$_POST['UIN'];
$class_enrollment->class_id = (int)$_POST['class_id'];
$class_enrollment->year = (int)$_POST['year'];
$class_enrollment->semester = App\Semester::from($_POST['semester']);
$class_enrollment->status = $_POST['status'];

$class_enrollment->create();

redirect("/pages/student_program_tracking.php");

exit;