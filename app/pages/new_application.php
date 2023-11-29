<?
// Shaz Maradya
require_once __DIR__ . '/../util.php';
require_once __DIR__ . '/../models/program.php';

include __DIR__ . '/../templates/header.php';
use function App\redirect;
use function App\redirectIfNotAuthenticated;


require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/student.php';

redirectIfNotAuthenticated();

use App\User;

$user = null;

$user = User::fromSession();
if (!$user) {
  throw new Exception("Invalid user id");
  redirect("/pages/login.php?error=Invalid_user_id");
}

?>

<form method="post" action="/forms/create_application.php">
  <h1>New Application</h1>
  <div>
    <label for="name">Program Name:</label><br>
    <select id="name" name="program_num">
        <? foreach (App\Program::all() as $program) { ?>
            <option value="<?= $program->program_num ?>"><?= $program->name ?></option>
        <? } ?>
    </select>
  </div>
  <div>
    <label for="uncom_cert">Are you currently enrolled in other uncompleted certifications sponsored by the Cybersecurity Center? </label><br/>
    <textarea id="uncom_cert" name="uncom_cert" rows="4" cols="40"></textarea>
  </div>
  <div>
    <label for="com_cert">Have you completed anycybersecurity industry certifications via the Cybersecurity Center? </label><br/>
    <textarea id="com_cert" name="com_cert" rows="4" cols="40"></textarea>
  </div>
  <div>
    <label for="purpose">Purpose Statement </label><br/>
    <textarea id="purpose" name="purpose" rows="8" cols="80"></textarea>
  </div>
  <input type="hidden" name="UIN" value="<?= $user->UIN ?>" />
  <input type="submit" value="Create" onsubmit="" />
</form>