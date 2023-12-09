<?
// Tanya Trujillo
require_once '../models/intern_app.php';
require_once '../util.php';

use function App\redirect;
$intern_app = new App\Intern_App();

if (isset($_POST['intern_id'])) {
    $intern_id = $_POST['intern_id'];
} else {
    $intern_id = 0;
}


$is_gov = isset($_POST['is_gov']) ? 1 : 0;


$intern_app->UIN = (int)$_POST['UIN'];
//$intern_app->intern_id = (int)$_POST['intern_id'];
$intern_app->year = (int)$_POST['year'];
$intern_app->status = $_POST['status'];
$intern_app->is_gov = (int)$_POST['is_gov'];
$intern_app->intern_name = $_POST['intern_name'];
$intern_app->intern_description = $_POST['intern_description'];

$intern_app->create();

redirect("/pages/student_program_tracking.php");

exit;