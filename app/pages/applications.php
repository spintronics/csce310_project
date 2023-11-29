<?php
// Shaz Maradya

require_once __DIR__ . '/../util.php';

use function App\redirect;
use function App\redirectIfNotAuthenticated;


require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/student.php';
require_once __DIR__ . '/../models/application.php';
require_once __DIR__ . '/../models/program.php';


redirectIfNotAuthenticated();

use App\User;
use App\UserType;
use App\Application;


include __DIR__ . '/../templates/header.php';

$user = null;
$student = null;

$user = User::fromSession();
if (!$user) {
  throw new Exception("Invalid user id");
  redirect("/pages/login.php?error=Invalid_user_id");
}
if ($user->user_type == UserType::Student) {
  $student = App\Student::find($user->UIN);
  if (!$student) {
    $student = new App\Student();
  }
}
$applications = App\Application::all($user->UIN);
$program_mapping = App\Program::nameMapping();

?>

<h1>All Applications</h1>
<a href='/pages/new_application.php'>New Application</a>
<div class="table-responsive user-table">
  <table class="table" style="table-layout: fixed;">
    <thead>
      <tr>
        <th style="width: 10%;">Application Number</th>
        <th style="width: 10%;">Program Name</th>
        <th style="width: 15%;">Uncompleted Certs</th>
        <th style="width: 15%;">Completed Certs</th>
        <th style="width: 25%;">Purpose Statement</th>
        <th style="width: 8%;">Status</th>
        <th>Actions</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <? foreach ($applications as $application) : ?>
        <form action="/forms/application_action.php" method="post">
          <tr>
            <td>
                <?= $application->app_num ?>
            </td>
            <td>
                <?= $program_mapping[$application->program_num] ?>
            </td>
            <td>
              <textarea type="text" name="uncom_cert" rows="10" cols="20" ><?= $application->uncom_cert ?></textarea>
            </td>
            <td>
                <textarea type="text" name="com_cert" rows="10" cols="20"><?= $application->com_cert ?></textarea>
            </td>
            <td>
                <textarea type="text" name="purpose" rows="12" cols="40"><?= $application->purpose_statement ?></textarea>
            </td>
            <td>
                Pending
            </td>
            <td>
              <select name="action">
                <option value="update">Update</option>
                <option value="delete">Delete</option>
              </select>
            </td>
            <td>
                <input type="hidden" name="app_num" value="<?= $application->app_num ?>"/>
              <input type="submit" value="Submit" onsubmit="" />
            </td>
          </tr>
      </form>
      <? endforeach ?>
    </tbody>
  </table>
</div>