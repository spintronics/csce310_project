<?php
// Kevin Brown

namespace App;

require_once __DIR__ . '/../util.php';

class Gender
{
  public int $id;
  public string $name;

  public static function find($id)
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM gender WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $gender = $result->fetch_assoc();
    $stmt->close();
    $db->close();
    return Gender::fromResult($gender);
  }

  public static function findByName($name)
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM gender WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    $gender = $result->fetch_assoc();
    $stmt->close();
    $db->close();
    return Gender::fromResult($gender);
  }

  public static function all()
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM gender");
    $stmt->execute();
    $result = $stmt->get_result();
    $genders = [];
    while ($gender = $result->fetch_assoc()) {
      $genders[] = Gender::fromResult($gender);
    }
    $stmt->close();
    $db->close();
    return $genders;
  }

  public static function fromResult($result)
  {
    $gender = new Gender();
    $gender->id = $result['id'];
    $gender->name = $result['name'];
    return $gender;
  }
}
