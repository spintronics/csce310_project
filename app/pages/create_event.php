<?php
// Saddy
require_once __DIR__ . '/../util.php';
require_once __DIR__ . '/../models/program.php';
require_once __DIR__ . '/../models/event.php';

include __DIR__ . '/../templates/header.php';

namespace App;
use App\User;
use function App\redirect;


$user = User::fromSession();
if (!$user || !$user->isAdmin()) {
    // User is not an admin or not logged in
    redirect("/pages/home.php"); // Redirect to a different page
    exit;
}
?>

<form method="post" action="/forms/create_event_action.php">
  <h1>Create New Event</h1>
  <div>
    <label for="event_type">Event Type:</label><br>
    <input type="text" id="event_type" name="event_type">
  </div>
  <div>
    <label for="start_date">Start Date:</label><br>
    <input type="date" id="start_date" name="start_date">
  </div>
  <div>
    <label for="end_date">End Date:</label><br>
    <input type="date" id="end_date" name="end_date">
  </div>
  <div>
    <label for="time">Time:</label><br>
    <input type="time" id="time" name="time">
  </div>
  <div>
    <label for="location">Location:</label><br>
    <input type="text" id="location" name="location">
  </div>
  <div>
    <label for="program_num">Program:</label><br>
    <select id="program_num" name="program_num">
        <?php foreach (App\Program::all() as $program) { ?>
            <option value="<?= $program->program_num ?>"><?= $program->name ?></option>
        <?php } ?>
    </select>
  </div>
  <input type="submit" value="Create Event" />
</form>
