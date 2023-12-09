<?
// Saddy Khakimova
namespace App;

class Event
{
    public int $event_id;
    public int $UIN;
    public int $program_num;
    public string $start_date;
    public string $time; // Time field added
    public string $location;
    public string $end_date;
    public string $event_type;

    public function create()
    {
        $db = openConnection();
        $stmt = $db->prepare("INSERT INTO event (UIN, program_num, start_date, time, location, end_date, event_type) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisssss", $this->UIN, $this->program_num, $this->start_date, $this->time, $this->location, $this->end_date, $this->event_type);
        $stmt->execute();
        $this->event_id = $db->insert_id;
        $stmt->close();
        $db->close();
    }

    public function update()
    {
        $db = openConnection();
        $stmt = $db->prepare("UPDATE event SET UIN=?, program_num=?, start_date=?, time=?, location=?, end_date=?, event_type=? WHERE event_id=?");
        $stmt->bind_param("iisssssi", $this->UIN, $this->program_num, $this->start_date, $this->time, $this->location, $this->end_date, $this->event_type, $this->event_id);
        $stmt->execute();
        $stmt->close();
        $db->close();
    }

    public function delete()
    {
        $db = openConnection();
        $stmt = $db->prepare("DELETE FROM event WHERE event_id=?");
        $stmt->bind_param("i", $this->event_id);
        $stmt->execute();
        $stmt->close();
        $db->close();
    }

    public static function get($id)
    {
        $db = openConnection();
        $stmt = $db->prepare("SELECT * FROM event WHERE event_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $eventData = $result->fetch_assoc();
        $stmt->close();
        $db->close();

        if ($eventData) {
            $event = new Event();
            $event->event_id = $eventData['event_id'];
            $event->UIN = $eventData['UIN'];
            $event->program_num = $eventData['program_num'];
            $event->start_date = $eventData['start_date'];
            $event->time = $eventData['time'];
            $event->location = $eventData['location'];
            $event->end_date = $eventData['end_date'];
            $event->event_type = $eventData['event_type'];
            return $event;
        } else {
            return null;
        }
    }

    public static function all()
    {
        $db = openConnection();
        $stmt = $db->query("SELECT * FROM event");
        $events = [];
        while ($eventData = $stmt->fetch_assoc()) {
            $event = new self();
            $event->event_id = $eventData['event_id'];
            $event->UIN = $eventData['UIN'];
            $event->program_num = $eventData['program_num'];
            $event->start_date = $eventData['start_date'];
            $event->time = $eventData['time'];
            $event->location = $eventData['location'];
            $event->end_date = $eventData['end_date'];
            $event->event_type = $eventData['event_type'];
            $events[] = $event;
        }
        $db->close();
        return $events;
    }

}
