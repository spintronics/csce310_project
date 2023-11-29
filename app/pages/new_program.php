<?
// Shaz Maradya
require_once __DIR__ . '/../util.php';
require_once __DIR__ . '/../models/program.php';

include __DIR__ . '/../templates/header.php';

?>

<form method="post" action="/forms/create_program.php">
  <h1>Create Program</h1>
  <div>
    <label for="name">Program Name:</label><br>
    <input type="text" id="name" name="name" required />
  </div>
  <div>
    <label for="description">Description:</label><br/>
    <textarea id="description" name="description" rows="5" cols="40"></textarea>
  </div>
  <input type="submit" value="Create" onsubmit="" />
</form>