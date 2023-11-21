<?php
// Kevin Brown

require_once __DIR__ . '/../util.php';

use function App\redirect;
use function App\redirectIfNotAuthenticated;


require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/student.php';

redirectIfNotAuthenticated();

use App\User;
use App\UserType;

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
// echo "cookie user id: " . $_COOKIE['user_uin'] . "<br />";
// echo json_encode($user);

?>
User Status: <?= $user->active_account ? "Active" : "Inactive" ?>
<? if ($user->active_account) { ?>
  <a href="/actions/deactivate_account.php">Deactivate</a>
<? } ?>
<br />
<h2> User Profile </h2>
<form method="post" action="/forms/update_user.php">
  <div>
    <label for="UIN">UIN</label>
    <input type="number" name="UIN" value="<?= $user->UIN ?>" required disabled />
  </div>
  <div>
    <label for="email">Email</label>
    <input type="email" name="email" value="<?= $user->email ?>" required />
  </div>
  <div>
    <label for="password">Username</label>
    <input type="text" name="username" value="<?= $user->username ?>" required />
  </div>
  <div>
    <label for="password">Password</label>
    <input type="password" name="password" value="<?= $user->password ?>" required />
  </div>
  <div>
    <label for="first_name">First Name</label>
    <input type="text" name="first_name" value="<?= $user->first_name ?>" required />
  </div>
  <div>
    <label for="m_initial">Middle Initial</label>
    <input type="text" name="m_initial" value="<?= $user->m_initial ?>" required />
  </div>
  <div>
    <label for="last_name">Last Name</label>
    <input type="text" name="last_name" value="<?= $user->last_name ?>" required />
  </div>
  <div>
    <label for="discord_name">Discord Name</label>
    <input type="text" name="discord_name" value="<?= $user->discord_name ?>" required />
  </div>
  <? if ($user->user_type == UserType::Admin) { ?>
    <div>
      <label for="user_type">Role</label>
      <select name="user_type" required>
        <? foreach (UserType::cases() as $user_type) { ?>
          <option value="<?= $user_type->value ?>" <? if ($user->user_type == $user_type) echo "selected"; ?>><?= $user_type->value ?></option>
        <? } ?>
      </select>
    </div>
  <? } ?>
  </div>
  <input type="submit" value="Save" onsubmit="" />
</form>

<br />

<? if ($student) { ?>
  <h2> Student Profile </h2>
  <form method="post" action="/forms/update_student.php">
    <div>
      <label for="gender">Gender</label>
      <select name="gender">
        <? foreach (App\Gender::cases() as $gender) { ?>
          <option value="<?= $gender->value ?>" <? if ($gender == $student->gender) echo "selected"; ?>><?= $gender->value ?></option>
        <? } ?>
      </select>
    </div>
    <div>
      <label for="hispanic_latino">Hispanic or Latino</label>
      <input type="checkbox" name="hispanic_latino" />
    </div>
    <div>
      <label for="race">Race</label>
      <select name="race">
        <? foreach (App\Race::cases() as $race) { ?>
          <option value="<?= $race->value ?>" <? if ($race == $student->race) echo "selected"; ?>><?= $race->value ?></option>
        <? } ?>
      </select>
    </div>
    <div>
      <label for="us_citizen">US Citizen</label>
      <input type="checkbox" name="us_citizen" <? if ($student->us_citizen) echo "checked"; ?> />
    </div>
    <div>
      <label for="date_of_birth">Date of Birth</label>
      <input type="date" name="date_of_birth" required value="<?= $student->date_of_birth->format("Y-m-d") ?>" />
    </div>
    <div>
      <label for="gpa">GPA</label>
      <input type="number" min="0" max="4" name="gpa" required value="<?= $student->gpa ?>" />
    </div>
    <div>
      <label for="major">Major</label>
      <input type="text" name="major" required value="<?= $student->major ?>" />
    </div>
    <div>
      <label for="minor_1">Minor 1</label>
      <input type="text" name="minor_1" required value="<?= $student->minor_1 ?>" />
    </div>
    <div>
      <label for="minor_2">Minor 2</label>
      <input type="text" name="minor_2" required value="<?= $student->minor_2 ?>" />
    </div>
    <div>
      <label for="expected_graduation">Expected Graduation</label>
      <input type="date" name="expected_graduation" required value="<?= $student->expected_graduation->format("Y-m-d") ?>" />
    </div>
    <div>
      <label for="school">School</label>
      <input type="text" name="school" required value="<?= $student->school ?>" />
    </div>
    <div>
      <label for="current_classification">Current Classification</label>
      <select name="current_classification">
        <? foreach (App\StudentClassification::cases() as $classification) { ?>
          <option value="<?= $classification->value ?>" <? if ($classification == $student->current_classification) echo "selected"; ?>><?= $classification->value ?></option>
        <? } ?>
      </select>
    </div>

    <input type="submit" value="Save" onsubmit="" />
  </form>

<? } ?>