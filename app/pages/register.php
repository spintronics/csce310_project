<?php
// Kevin Brown
include __DIR__ . '/../templates/header.php';
?>

<form method="post" action="/forms/register_user.php">
  <h1>Register</h1>
  <input type="text" name="email" placeholder="username" required="required" />
  <input type="password" name="password" placeholder="password" required="required" />
  <input type="submit" value="Register" onsubmit="" />
</form>