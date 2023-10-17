<?php
require_once __DIR__ . '/../models/user.php';

use App\User;

include __DIR__ . '/../templates/header.php';
?>

<h1>List of Users</h1>
<ul>
  <?php
  $users = User::findAll();
  foreach ($users as $user) {
    echo "<li>" . $user['email'] . "</li>";
  }
  ?>

</ul>