<?
// Shaz Maradya
require_once __DIR__ . '/../util.php';
require_once '../models/application.php';

$action = $_POST['action'];
$app = App\Application::get((int)$_POST['app_num']);

switch ($action) {
    case 'accept':
        $app->status = App\Status::Accepted;
        $app->update();
        $app->accept();
        App\redirect("/pages/programs.php?id=" . $app->program_num);
        break;
    case 'reject':
        $app->status = App\Status::Rejected;
        $app->update();
        App\redirect("/pages/programs.php?id=" . $app->program_num);
        break;
    default:
        App\redirect("/pages/programs.php?id=" . $app->program_num);
        break;
}
  