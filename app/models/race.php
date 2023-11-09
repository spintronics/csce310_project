<?php
// Kevin Brown
namespace App;


require_once __DIR__ . '/../util.php';
require_once __DIR__ . '/role.php';

class Race
{
  public int $id;
  public string $name;

  public function save()
  {
    $db = openConnection();
    $stmt = $db->prepare("INSERT INTO race (name) VALUES (?)");
    $stmt->bind_param("s", $this->name);
    $stmt->execute();
    $stmt->close();
    $db->close();
  }



  public static function find($id)
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM race WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $race = $result->fetch_assoc();
    $stmt->close();
    $db->close();
    return Race::fromResult($race);
  }

  public static function all()
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM race");
    $stmt->execute();
    $result = $stmt->get_result();
    $races = [];
    while ($race = $result->fetch_assoc()) {
      $races[] = Race::fromResult($race);
    }
    $stmt->close();
    $db->close();
    return $races;
  }

  public static function fromResult($result)
  {
    $race = new Race();
    $race->id = $result['id'];
    $race->name = $result['name'];
    return $race;
  }
}
