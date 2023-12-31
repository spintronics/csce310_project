<?
// Shaz Maradya
require_once __DIR__ . '/../util.php';
require_once __DIR__ . '/../models/program.php';
require_once __DIR__ . '/../models/application.php';

use function App\redirect;

include __DIR__ . '/../templates/header.php';
$id = isset($_GET['id']) ? $_GET['id'] : null;
$program = App\Program::get($id);

if (!$id) {
    App\redirect("/pages/admin_programs.php");
    exit;
}

$apps = App\Application::programApps($id);
$members = App\Program::members($id);

?>
<h1>Program Information and Applications</h1>
<form method="post" action="/forms/admin_program_action.php">
    <div>
        <label for="name">Program Name:</label><br>
        <input type="text" id="name" name="name" value="<?= $program->name ?>" required />
    </div>
    <div>
        <label for="description">Description:</label><br/>
        <textarea id="description" name="description" rows="5" cols="40"><?= $program->description ?></textarea>
    </div>
    <select name="action">
            <option value="update">Update</option>
            <option value="delete">Delete</option>
    </select>
    <input type="hidden" name="id" value="<?= $program->program_num ?>" />
    <input type="submit" value="submit" onsubmit="" />
</form>

<h2>Pending Applications</h2>
<div class="table-responsive user-table">
  <table class="table" style="table-layout: fixed;">
    <thead>
      <tr>
        <th style="width: 25%;">Student UIN</th>
        <th style="width: 25%;">First Name</th>
        <th style="width: 25%;">Last Name</th>

        <th></th>
      </tr>
    </thead>
    <tbody>
      <? foreach ($apps as $app) : ?>
        <?
        if ($app->status == App\Status::Pending) {
        ?>
          <tr>
            <td>
                <?= $app->UIN ?>
            </td>
            <td>
                <?= $app->first_name ?>
            </td>
            <td>
                <?= $app->last_name ?>
            </td>
            <td>
                <a href='/pages/view_application.php?id=<?= $app->app_num ?>'>See Application</a>
            </td>
          </tr>
          <? }?>
      <? endforeach ?>
    </tbody>
  </table>
</div>

<h2>Current Members</h2>
<div class="table-responsive user-table">
  <table class="table" style="table-layout: fixed;">
    <thead>
      <tr>
        <th style="width: 25%;">Student UIN</th>
        <th style="width: 25%;">Major</th>
        <th style="width: 25%;">GPA</th>
        <th style="width: 25%;">School</th>
        <th style="width: 25%;">Classification</th>
      </tr>
    </thead>
    <tbody>
      <? foreach ($members as $member) : ?>
          <tr>
            <td>
                <?= $member->UIN ?>
            </td>
            <td>
                <?= $member->major ?>
            </td>
            <td>
              <?= $member->gpa ?>
            </td>
            <td>
              <?= $member->school ?>
            </td>
            <td>
              <?= $member->current_classification->value ?>
            </td>
          </tr>
      <? endforeach ?>
    </tbody>
  </table>
</div>