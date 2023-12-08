<?php
// Saddy Khakimova
require_once __DIR__ . '/../models/event.php';
require_once __DIR__ . '/../util.php';

use App\Event;
use function App\redirect;

if (!isset($_POST['UIN'], $_POST['program_num'], $_POST['start_date'], $_POST['time'], $_POST['location'], $_POST['end_date'], $_POST['event_type'])) {
    redirect("/pages/create_event.php?error=Please fill all required fields.");
    exit;
}

$event = new App\Event();

$event->UIN = (int)$_POST['UIN'];
$event->program_num = (int)$_POST['program_num'];
$event->start_date = $_POST['start_date'];
$event->time = $_POST['time'];
$event->location = $_POST['location'];
$event->end_date = $_POST['end_date'];
$event->event_type = $_POST['event_type'];


try {

    $event->create();

    redirect("/pages/admin_events.php");
} catch (Exception $e) {
    
    redirect("/pages/create_event.php?error=" . urlencode($e->getMessage()));
    exit;
}

