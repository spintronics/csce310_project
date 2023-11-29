<?
// Shaz Maradya
require_once __DIR__ . '/../util.php';
require_once '../models/application.php';

$action = $_POST['action'];
$app = App\Application::get((int)$_POST['app_num']);

switch ($action) {
    case 'update':
        $app->uncom_cert = $_POST['uncom_cert'];
        $app->com_cert = $_POST['com_cert'];
        $app->purpose_statement = $_POST['purpose'];
        $app->update();
        App\redirect("/pages/applications.php");
        break;
    case 'delete':
        $app->delete();
        App\redirect("/pages/applications.php");
        break;
    default:
        App\redirect("/pages/applications.php");
        break;
}
  