<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php';

use App\User;
?>

<form method="post" action="pages/forms/register_user.php">
  <h1>Register</h1>
  <input type="text" name="email" placeholder="username" required="required" />
  <input type="password" name="password" placeholder="password" required="required" />
  <input type="submit" value="Register" onsubmit="" />
</form>

<!-- list of users -->
<h1>List of Users</h1>
<ul>
  <?php
  $users = User::findAll();
  foreach ($users as $user) {
    echo "<li>" . $user['email'] . "</li>";
  }
  ?>

</ul>