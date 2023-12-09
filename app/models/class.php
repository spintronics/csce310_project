<?php
// Tanya Trujillo

namespace App;

require_once __DIR__ . '/../util.php';

use App\Exception;

class Classes
{
  public ?int $class_id = 0;
  public ?string $name = "";
  public ?string $description = "";
  public ?string $type = "";

  public function create()
  {
    $db = openConnection();
    $user_type = (string) $this->user_type->value;
    $stmt = $db->prepare("INSERT INTO class (name, description, type) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $this->name, $this->description, $this->type);
    $stmt->execute();
    $stmt->close();
    $db->close();
  }

  public function update()
  {
    $db = openConnection();
    $user_type = (string) $this->user_type->value;
    $stmt = $db->prepare("UPDATE class SET name=?, description=?, type=? WHERE class_id=?");
    $stmt->bind_param("sssi", $this->name, $this->description, $this->type, $this->class_id);
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
    $stmt = $db->prepare("DELETE FROM class_enrollment WHERE class_id=?");
    $stmt->bind_param("i", $this->class_id);
    $success = $stmt->execute();

    $stmt = $db->prepare("DELETE FROM class WHERE class_id=?");
    $stmt->bind_param("i", $this->class_id);
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
    $stmt = $db->prepare("SELECT * FROM class WHERE class_id = ?");
    $stmt->bind_param("i", $this->class_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $class = $result->fetch_assoc();
    $stmt->close();
    $db->close();
    if ($class) {
      $this->update();
    } else {
      $this->create();
    }
  }

  // public static function count()
  // {
  //   $db = openConnection();
  //   $stmt = $db->prepare("SELECT COUNT(*) FROM user");
  //   $stmt->execute();
  //   $result = $stmt->get_result();
  //   $count = $result->fetch_assoc()['COUNT(*)'];
  //   $stmt->close();
  //   $db->close();
  //   return $count;
  // }

  public static function findByName($name)
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM class WHERE name = ? LIMIT 1");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    $db->close();
    if (!$class) {
      return null;
    }
    return User::fromResult($class);
  }

  // public static function findByEmail($email)
  // {
  //   $db = openConnection();
  //   $stmt = $db->prepare("SELECT * FROM user WHERE email = ? LIMIT 1");
  //   $stmt->bind_param("s", $email);
  //   $stmt->execute();
  //   $result = $stmt->get_result();
  //   $user = $result->fetch_assoc();
  //   $stmt->close();
  //   $db->close();
  //   if (!$user) {
  //     return null;
  //   }
  //   return User::fromResult($user);
  // }

  public static function find($class_id)
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM class WHERE class_id = ? LIMIT 1");
    $stmt->bind_param("i", $class_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $class = $result->fetch_assoc();
    $stmt->close();
    $db->close();
    if (!$class) {
      return null;
    }
    return Classes::fromResult($class);
  }

  public static function all()
  {
    $db = openConnection();
    $stmt = null;
    // sql injection vulnerability
    $stmt = $db->prepare("SELECT * from class");
    $stmt->execute();
    $result = $stmt->get_result();
    $classes = [];
    while ($class = $result->fetch_assoc()) {
      $classes[] = Classes::fromResult($class);
    }
    $stmt->close();
    $db->close();
    return $classes;
  }

  public static function fromResult($result)
  {
    $class = new Classes();
    $class->class_id = $result['class_id'];
    $class->name = $result['name'];
    $class->description = $result['description'];
    $class->type = $result['type'];

    return $class;
  }
}