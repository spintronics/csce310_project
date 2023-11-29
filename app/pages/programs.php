<?
// Shaz Maradya
require_once __DIR__ . '/../util.php';
require_once __DIR__ . '/../models/program.php';
use function App\redirect;

include __DIR__ . '/../templates/header.php';
$id = isset($_GET['id']) ? $_GET['id'] : null;
$program = App\Program::get($id);

if (!$id) {
    App\redirect("/pages/admin_programs.php");
    exit;
  }

?>

<form method="post" action="/forms/update_program.php">
    <div>
        <label for="name">Program Name:</label><br>
        <input type="text" id="name" name="name" value="<?= $program->name ?>" required />
    </div>
    <div>
        <label for="description">Description:</label><br/>
        <textarea id="description" name="description" rows="5" cols="40"><?= $program->description ?></textarea>
    </div>
    <input type="hidden" name="id" value="<?= $program->program_num ?>" />
    <input type="submit" value="Update" onsubmit="" />
</form>