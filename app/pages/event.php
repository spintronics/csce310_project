<?php

require_once __DIR__ . '/../util.php';
use function App\redirect;
use function App\redirectIfNotAuthenticated;

require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/event.php';
require_once __DIR__ . '/../models/program.php';

redirectIfNotAuthenticated();

use App\User;
use App\Event;

include __DIR__ . '/../templates/header.php';

$user = User::fromSession();
if (!$user) {
    throw new Exception("Invalid user id");
    redirect("/pages/login.php?error=Invalid_user_id");
}

// Fetch all events
$events = Event::all();
$program_mapping = App\Program::nameMapping();

?>

<h1>All Events</h1>
<?php if ($user && $user->isAdmin()) : ?>
    <a href='/pages/create_event.php'>Create New Event</a>
<?php endif; ?>
<div class="table-responsive event-table">
    <table class="table" style="table-layout: fixed;">
        <thead>
            <tr>
                <th>Event ID</th>
                <th>Event Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Time</th>
                <th>Location</th>
                <th>Program Name</th>
                <?php if ($user && $user->isAdmin()) : ?>
                <th>Actions</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($events as $event) : ?>
                <tr>
                    <td><?= htmlspecialchars($event->event_id) ?></td>
                    <td><?= htmlspecialchars($event->event_type) ?></td>
                    <td><?= htmlspecialchars($event->start_date) ?></td>
                    <td><?= htmlspecialchars($event->end_date) ?></td>
                    <td><?= htmlspecialchars($event->time) ?></td>
                    <td><?= htmlspecialchars($event->location) ?></td>
                    <td><?= htmlspecialchars($program_mapping[$event->program_num]) ?></td>
                    <?php if ($user && $user->isAdmin()) : ?>
                    <td>
                        <a href='/pages/edit_event.php?id=<?= $event->event_id ?>'>Edit</a>
                        <a href='/forms/delete_event_action.php?event_id=<?= $event->event_id ?>' onclick="return confirm('Are you sure you want to delete this event?')">Delete</a>
                    </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
