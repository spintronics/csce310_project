<?php
// Tanya Trujillo

namespace App;

require_once __DIR__ . '/../util.php';

use App\Exception;

class Internship
{
  public ?int $intern_id = 0;
  public ?string $name = "";
  public ?string $description = "";
  public ?int $is_gov = 0;

  public function create()
  {
    $db = openConnection();
    $stmt = $db->prepare("INSERT INTO internship (name, description, is_gov) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $this->name, $this->description, $this->is_gov);
    $stmt->execute();
    $stmt->close();
    $db->close();
  }

  public function update()
  {
    $db = openConnection();
    $stmt = $db->prepare("UPDATE internship SET name=?, description=?, is_gov=? WHERE intern_id=?");
    $stmt->bind_param("ssii", $this->name, $this->description, $this->is_gov, $this->intern_id);
    $success = $stmt->execute();
    if (!$success) {
      throw new \Exception($stmt->error);
    }
    $stmt->close();
    $db->close();
  }

  public function delete()
  {
    $db = openConnection();
    $stmt = $db->prepare("DELETE FROM intern_app WHERE intern_id=?");
    $stmt->bind_param("i", $this->intern_id);
    $success = $stmt->execute();

    $stmt = $db->prepare("DELETE FROM internship WHERE intern_id=?");
    $stmt->bind_param("i", $this->intern_id);
    $success = $stmt->execute();

    if (!$success) {
      throw new \Exception($stmt->error);
    }
    $stmt->close();
    $db->close();
  }

  public function save()
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM internship WHERE intern_id = ?");
    $stmt->bind_param("i", $this->intern_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $internship = $result->fetch_assoc();
    $stmt->close();
    $db->close();
    if ($internship) {
      $this->update();
    } else {
      $this->create();
    }
  }

  public static function findByName($name)
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM internship WHERE name = ? LIMIT 1");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    $internship = $result->fetch_assoc();
    $stmt->close();
    $db->close();
    if (!$internship) {
      return null;
    }
    return Internship::fromResult($internship);
  }

  public static function find($intern_id)
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM internship WHERE intern_id = ? LIMIT 1");
    $stmt->bind_param("i", $intern_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $internship = $result->fetch_assoc();
    $stmt->close();
    $db->close();
    if (!$internship) {
      return null;
    }
    return Internship::fromResult($internship);
  }

  public static function all()
  {
    $db = openConnection();
    $stmt = null;
    // sql injection vulnerability
    $stmt = $db->prepare("SELECT * from internship");
    $stmt->execute();
    $result = $stmt->get_result();
    $internships = [];
    while ($internship = $result->fetch_assoc()) {
      $internships[] = Internship::fromResult($internship);
    }
    $stmt->close();
    $db->close();
    return $internships;
  }

  public static function fromResult($result)
  {
    $internship = new Internship();
    $internship->intern_id = $result['intern_id'];
    $internship->name = $result['name'];
    $internship->description = $result['description'];
    $internship->is_gov = $result['is_gov'];

    return $internship;
  }
}