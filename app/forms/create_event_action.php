<?php
// Saddy Khakimova
session_abort();
require_once __DIR__ . '/../models/event.php';
require_once __DIR__ . '/../models/user.php';

require_once __DIR__ . '/../util.php';

use App\Event;
use App\User;
use function App\redirect;

$event = new App\Event();

$user = null;

$user = User::fromSession();
if (!$user) {
  throw new Exception("Invalid user id");
  redirect("/pages/login.php?error=Invalid_user_id");
}

if( isset( $_GET["event_id"] ) ) {
    $event->event_id = $_GET["event_id"];
    $event->delete();
    redirect("/pages/event.php");
    exit;
}

if( isset( $_GET["join_event_id"] ) ) {
    $event->event_event_id = $_GET["join_event_id"];
    $event->user_UIN = $user->UIN;
    $event->eventTracking();
    redirect("/pages/event.php");
    exit;
}

$event->UIN = (int)$_POST['UIN'];
$event->program_num = (int)$_POST['program_num'];
$event->start_date = $_POST['start_date'];
$event->time = $_POST['time'];
$event->location = $_POST['location'];
$event->end_date = $_POST['end_date'];
$event->event_type = $_POST['event_type'];

if( isset( $_POST["event_id"] ) && !empty( $_POST["event_id"] ) ) {
    $event->event_id = $_POST['event_id'];
}

if ( !$event->UIN || empty( $event->UIN )) {
    redirect("/pages/create_event.php?error=Missing UIN");
    exit;
}

try {
    if( !empty( $event->event_id ) ) {
        $event->update();
    } else {
        $event->create();
    }
    redirect("/pages/event.php");
    exit;
} catch (Exception $e) {

    redirect("/pages/create_event.php?error=" . urlencode($e->getMessage()));
    exit;
}

