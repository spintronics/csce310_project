<?
// Shaz Maradya
require_once '../models/program.php';
require_once '../util.php';

use function App\redirect;
use App\Program;

$action = $_POST['action'];


$id = isset($_POST['id']) ? (int)$_POST['id'] : null;
if(!$id){
    App\redirect("/pages/login.php");
    exit;
}
$program = Program::get((int)$_POST['id']);

if (!$program) {
    App\redirect("/pages/admin_programs.php");
    exit;
  }


switch ($action) {
  case 'update':
    $program->name = $_POST['name'];
    $program->description = $_POST['description'];
    $program->update();
    App\redirect("/pages/admin_programs.php");
    break;
  case 'delete':
    $program->delete();
    App\redirect("/pages/admin_programs.php");
    break;
  default:
    App\redirect("/pages/admin_programs.php");
    break;
}

exit;
