<?php
// Kevin Brown
namespace App;

require_once __DIR__ . '/../util.php';

class Role
{
  public int $id;
  public string $name;

  public function save()
  {
    $db = openConnection();
    $stmt = $db->prepare("INSERT INTO role (name) VALUES (?)");
    $stmt->bind_param("s", $this->name);
    $stmt->execute();
    $stmt->close();
    $db->close();
  }

  public static function find(int $id)
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM `role` WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $role = $result->fetch_assoc();
    $stmt->close();
    $db->close();
    return Role::fromResult($role);
  }

  public static function all()
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM `role`");
    $stmt->execute();
    $result = $stmt->get_result();
    $roles = [];
    while ($role = $result->fetch_assoc()) {
      $roles[] = Role::fromResult($role);
    }
    $stmt->close();
    $db->close();
    return $roles;
  }

  public static function fromResult($result)
  {
    $role = new Role();
    $role->id = $result['id'];
    $role->name = $result['name'];
    return $role;
  }
}
