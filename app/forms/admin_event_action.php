<?php
// Saddy Khakimova
require_once __DIR__ . '/../util.php';
require_once __DIR__ . '/../models/event.php';

use App\Event;
use function App\redirect;

$action = $_POST['action'];
$eventId = isset($_POST['event_id']) ? (int)$_POST['event_id'] : null;

// Redirect if no event ID is provided for actions that require it
if (!$eventId && $action !== 'create') {
    redirect("/pages/admin_event.php");
    exit;
}

// Instantiate an Event object or fetch it if an ID is provided
$event = $eventId ? Event::get($eventId) : new Event();

// Switch based on the action provided
switch ($action) {
    case 'create':
        // Populate the event object with data from the form
        $event->UIN = $_POST['UIN']; // Or set from the session user
        $event->program_num = $_POST['program_num'];
        $event->start_date = $_POST['start_date'];
        $event->time = $_POST['time'];
        $event->location = $_POST['location'];
        $event->end_date = $_POST['end_date'];
        $event->event_type = $_POST['event_type'];
        $event->create();
        redirect("/pages/admin_event.php");
        break;

    case 'update':
        $event->start_date = $_POST['start_date'];
        $event->time = $_POST['time'];
        $event->location = $_POST['location'];
        $event->end_date = $_POST['end_date'];
        $event->event_type = $_POST['event_type'];
        $event->update();
        redirect("/pages/admin_event.php");
        break;

    case 'delete':
        $event->delete();
        redirect("/pages/admin_event.php");
        break;
    
    default:
        redirect("/pages/admin_event.php");
        break;
}

exit;
