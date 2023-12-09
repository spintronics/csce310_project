$db = App\openConnection();
$events = $db->query("SELECT * FROM event_details");
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
                
                <th>Actions</th>
         
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
                    <td>
                        <?php if ($user && $user->isAdmin()) : ?>
                        <a href='/pages/create_event.php?id=<?= $event->event_id ?>'>Edit</a>
                        <a href='/forms/create_event_action.php?event_id=<?= $event->event_id ?>' onclick="return confirm('Are you sure you want to delete this event?')">Delete</a>
                        <?php else: ?>
                            <? if( $event_obj->checkEventTracking( $user_data->UIN, $event->event_id) === false ): ?>
                            <a href='/forms/create_event_action.php?join_event_id=<?= $event->event_id ?>'>  Join </a>
                            <?php else: ?>
                                <?php echo "Joined" ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                    
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
