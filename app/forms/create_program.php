<?
// Shaz Maradya
require_once '../models/program.php';
require_once '../util.php';

use App\User;

use function App\redirect;
$program = new App\Program();

$program->name = $_POST['name'];
$program->description = $_POST['description'];
$program->create();

redirect("/pages/admin_programs.php");

exit;
