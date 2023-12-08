<?php
// register_for_event.php
require_once __DIR__ . '/../util.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Event.php';

use App\User;
use App\Event;

session_start();
$user = User::fromSession();

if (!$user) {
    redirect('/pages/login.php');
    exit;
}

if (!isset($_GET['event_id']) || empty($_GET['event_id'])) {
    redirect('/pages/error.php?message=' . urlencode("Event ID is required"));
    exit;
}

$event_id = (int) $_GET['event_id'];
$event_object = new Event();

if (!$event_object->get($event_id)) {
    redirect('/pages/error.php?message=' . urlencode("Event not found"));
    exit;
}

$db = openConnection();
$stmt = $db->prepare("INSERT INTO event_registrations (user_id, event_id) VALUES (?, ?)");
$stmt->bind_param("ii", $user->UIN, $event_id);

if ($stmt->execute()) {
    redirect('/pages/success.php?message=' . urlencode("Registered successfully"));
} else {
    redirect('/pages/error.php?message=' . urlencode("Error registering for the event"));
}

$stmt->close();
$db->close();
