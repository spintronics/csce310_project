<?php
// Tanya Trujillo
require_once __DIR__ . '/../models/user.php';

use App\User;

include __DIR__ . '/../templates/header.php';
?>

<form method="post" action="/pages/student_program_tracking.php">
  <h1>Enter A Student's UIN to View Their Progress</h1>
  <div>
    <label for="UIN"></label>
    <input type="int" name="student_uin" placeholder="UIN" required="required" />
  </div>
  <input type="submit" value="View" onsubmit="" />
</form>