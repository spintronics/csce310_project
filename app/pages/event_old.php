<?php
// Saddy

require_once __DIR__ . '/../util.php';
require_once __DIR__ . '/../models/event.php';

// Assuming that session_start() needs to be called, place it at the beginning of the file.
session_start();

use App\Event;

function openConnection()
{
  // Corrected the parameters order: host, username, password, database, port
  return new mysqli("db", "root", "admin", "mydb", 3306);
}

$event_object = new Event();

// Make sure that the all() method inside Event class returns an array.
$all_events = $event_object->all() ?? []; // Ensure it defaults to an array.

// read query params
$sort = App\getOr('sort', 'UIN');
$page = App\getOr('page', 1);
$filter = App\getOr('filter', null);

$users = [];

// Make sure there is no output before this include if session_start is inside header.php
include __DIR__ . '/../templates/header.php';
$count = 1;
?>

<h1>All Events</h1>
<a href='/pages/create_event.php'>Create New Event</a> <!-- Add this line -->
<table border="2" style="width: 30%">
    <tr>
        <td>#</td>
        <td>Event Type</td>
        <td>Start Date</td>
        <td>End Date</td>
    </tr>
<?php foreach($all_events as $event) { ?> 
    <tr>
        <td> <?php echo $count++; ?> </td>
        <td><?php echo htmlspecialchars($event->event_type); ?></td>
        <td><?php echo htmlspecialchars($event->start_date); ?></td>
        <td><?php echo htmlspecialchars($event->end_date); ?></td>
    </tr>
<?php } ?>
</table>
