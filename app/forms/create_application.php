<?
// Shaz Maradya
require_once '../models/application.php';
require_once '../util.php';

use function App\redirect;
$application = new App\Application();

$application->UIN = (int)$_POST['UIN'];
$application->program_num = (int)$_POST['program_num'];
$application->uncom_cert = $_POST['uncom_cert'];
$application->com_cert = $_POST['com_cert'];
$application->purpose_statement = $_POST['purpose'];


$application->create();

redirect("/pages/applications.php");

exit;