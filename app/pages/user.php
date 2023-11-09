<?php
// Kevin Brown

require_once __DIR__ . '/../util.php';

use function App\redirect;
use function App\redirectIfNotAuthenticated;


require_once __DIR__ . '/../models/user.php';

redirectIfNotAuthenticated();

use App\User;
use App\Role;
use App\Race;
use App\Gender;

include __DIR__ . '/../templates/header.php';

$user = null;

try {
  $user = User::fromSession();
  if (!$user) {
    throw new Exception("Invalid user id");
    redirect("/pages/login.php?error=Invalid_user_id");
  }
} catch (\Throwable $e) {
  redirect("/pages/login.php?error=Invalid_user_id");
  exit;
}
// echo "cookie user id: " . $_COOKIE['user_id'] . "<br />";
// echo json_encode($user);

?>

<form method="post" action="/forms/update_user.php">
  <div>
    <label for="email">Email</label>
    <input type="email" name="email" value="<?= $user->email ?>" disabled />
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
    <label for="last_name">Last Name</label>
    <input type="text" name="last_name" value="<?= $user->last_name ?>" required />
  </div>
  <div>
    <label for="hispanic_or_latino">Hispanic or Latino</label>
    <input type="checkbox" name="hispanic_or_latino" value="<?= $user->hispanic_or_latino ?>" />
  </div>
  <div>
    <label for="us_citizen">US Citizen</label>
    <input type="checkbox" name="us_citizen" value="<?= $user->us_citizen ?>" />
  </div>
  <div>
    <label for="date_of_birth">Date of Birth</label>
    <input type="date" name="date_of_birth" value="<?= date('Y-m-d', strtotime($user->date_of_birth)) ?>" required />
  </div>
  <div>
    <label for="phone">Phone</label>
    <input type="tel" name="phone" value="<?= $user->phone ?>" required pattern="[0-9]{10}" />
  </div>
  <? if ($user->role->name == 'admin') { ?>
    <div>
      <label for="role_id">Role</label>
      <select name="role_id" required>
        <? foreach (Role::all() as $role) { ?>
          <option value="<?= $role->id ?>" <? if ($user->role->id == $role->id) echo "selected"; ?>><?= $role->name ?></option>
        <? } ?>
      </select>
    </div>
  <? } ?>
  <div>
    <label for="race_id" required>Race</label>
    <select name="race_id" required>
      <? foreach (Race::all() as $race) { ?>
        <option value="<?= $race->id ?>" <? if ($user->race->id == $race->id) echo "selected"; ?>><?= $race->name ?></option>
      <? } ?>
    </select>
  </div>
  <div>
    <label for="gender_id" required>Gender</label>
    <select name="gender_id" required>
      <? foreach (Gender::all() as $gender) { ?>
        <option value="<?= $gender->id ?>" <? if ($user->gender->id == $gender->id) echo "selected"; ?>><?= $gender->name ?></option>

      <? } ?>
    </select>
  </div>
  <input type="submit" value="Save" onsubmit="" />
</form>