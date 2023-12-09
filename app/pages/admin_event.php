<?php
// Saddy Khakimova
require_once __DIR__ . '/../util.php';
require_once __DIR__ . '/../models/event.php';

include __DIR__ . '/../templates/header.php';

namespace App;
use App\Event;
use App\User;

// Fetch all events
$events = Event::all();

$user = User::fromSession();
?>

<h1>All Events</h1>
<?php if ($user && $user->isAdmin()) : ?>
    <a href='/pages/create_event.php'>Create New Event</a>
<?php endif; ?>
<div>
  <table border="1" style="width: 100%; margin-top: 20px;">
    <thead>
      <tr>
        <th>Event ID</th>
        <th>Event Type</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Time</th>
        <th>Location</th>
        <th>Program Number</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($events as $event) : ?>
          <tr>
            <td><?= $event->event_id ?></td>
            <td><?= $event->event_type ?></td>
            <td><?= $event->start_date ?></td>
            <td><?= $event->end_date ?></td>
            <td><?= $event->time ?></td>
            <td><?= $event->location ?></td>
            <td><?= $event->program_num ?></td>
            <td>
                <a href='/pages/edit_event.php?id=<?= $event->event_id ?>'>Edit</a>
                <!-- Optionally add a delete link -->
                <!-- <a href='/forms/delete_event_action.php?event_id=<?= $event->event_id ?>' onclick="return confirm('Are you sure?')">Delete</a> -->
            </td>
          </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
