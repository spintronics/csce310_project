<?php

namespace App;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php';



class User
{
  public $password;
  public $email;
  public $id;

  public function __construct($email, $password, $id = null)
  {
    $this->password = $password;
    $this->email = $email;
    $this->id = $id;
  }

  public function save()
  {
    $db = openConnection();
    $stmt = $db->prepare("INSERT INTO user (email, password, role_id, race_id) VALUES (?, ?, 1, 1)");
    $stmt->bind_param("ss", $this->email, $this->password);
    $stmt->execute();
    $stmt->close();
    $db->close();
  }



  public static function findById($id)
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM user WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    $db->close();
    return $user;
  }

  public static function findAll()
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM user");
    $stmt->execute();
    $result = $stmt->get_result();
    $users = [];
    while ($user = $result->fetch_assoc()) {
      $users[] = $user;
    }
    $stmt->close();
    $db->close();
    return $users;
  }
}
