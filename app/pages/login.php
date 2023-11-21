<?php
// Kevin Brown
require_once __DIR__ . '/../models/user.php';

use App\User;

include __DIR__ . '/../templates/header.php';
?>

<form method="post" action="/forms/login_user.php">
  <h1>Login</h1>
  <div>
    <label for="UIN"></label>
    <input type="text" name="UIN" placeholder="UIN" required="required" />
  </div>
  <div>
    <input type="password" name="password" placeholder="password" required="required" />
  </div>
  <input type="submit" value="Login" onsubmit="" />
</form>