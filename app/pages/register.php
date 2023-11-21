<?php
// Kevin Brown
include __DIR__ . '/../templates/header.php';
?>

<form method="post" action="/forms/register_user.php">
  <h1>Register</h1>
  <div>
    <input type="text" name="username" placeholder="username" required />
  </div>
  <div>
    <input type="text" name="UIN" placeholder="UIN" required minlength="9" />
  </div>
  <div>
    <input type="password" name="password" placeholder="password" required />
  </div>
  <input type="submit" value="Register" onsubmit="" />
</form>