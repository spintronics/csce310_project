<?php
// Saddy Khakimova
namespace App;

require_once __DIR__ . '/../util.php';
require_once __DIR__ . '/../models/program.php';
require_once __DIR__ . '/../models/event.php';

include __DIR__ . '/../templates/header.php';
use App\User;

$user = null;

$user = User::fromSession();
if (!$user) {
  throw new Exception("Invalid user id");
  redirect("/pages/login.php?error=Invalid_user_id");
}

use function App\redirect;

$event = new Event();
$submit_name = "";
$event_id = "";
$button_value = "Create Event";
$heading = "Create";

$user = User::fromSession();
if (!$user || !$user->isAdmin()) {
    redirect("/pages/home.php");
    exit;
}

// prepare for event update
if( isset( $_GET["id"] ) && !empty( $_GET["id"] )) {
  $event_id = $_GET["id"];
  $submit_name = "update";
  $this_event = $event->get( $event_id );
  $button_value = "Update Event";
  $heading = "Update";
}
?>

<form method="post" action="/forms/create_event_action.php">
  <h1><?php echo $heading ?> New Event</h1>
  <div>
    <label for="event_type">Event Type:</label><br>
    <input type="hidden" name="event_id" value="<?php echo $event_id ?>">
    <input type="text" id="event_type" value="<?php echo isset($this_event->event_type) ? $this_event->event_type : '' ?>" name="event_type">
  </div>

  <div>
    <label for="start_date">Start Date:</label><br>
    <input type="date" id="start_date" value="<?php echo isset($this_event->start_date) ? $this_event->start_date : '' ?>" name="start_date">
  </div>
  <div>
    <label for="end_date">End Date:</label><br>
    <input type="date" id="end_date" value="<?php echo isset($this_event->end_date) ? $this_event->end_date : '' ?>" name="end_date">
  </div>
  <div>
    <label for="time">Time:</label><br>
    <input type="time" id="time" value="<?php echo isset($this_event->time) ? $this_event->time : '' ?>" name="time">
  </div>
  <input type="hidden" name="UIN" value="<?= $user->UIN ?>" />
  <div>
    <label for="location">Location:</label><br>
    <input type="text" id="location" value="<?php echo isset($this_event->location) ? $this_event->location : '' ?>" name="location">
  </div>
  <div>
  <?php 

    ?>
    <label for="program_num">Program:</label><br>
    <select id="program_num" name="program_num">
    <option value="">--- Select ----</option>

        <?php foreach (Program::all() as $program) { ?>
         
            <option value="<?php echo $program->program_num ?>" <?php echo (isset( $this_event ) && $this_event->program_num === $program->program_num) ? "selected" : "" ?>><?php echo $program->name ?></option>
        <?php } ?>
    </select>
  </div>
  <input type="submit" name="<?php echo $submit_name;  ?>" value="<?php echo $button_value ?>">
</form>
