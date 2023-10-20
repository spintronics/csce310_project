<?php

namespace App;

use DateTime;

require_once __DIR__ . '/../util.php';
require_once __DIR__ . '/role.php';
require_once __DIR__ . '/race.php';


class User
{
  public string $password;
  public string $email;
  public int $id;
  public string $first_name;
  public string $last_name;
  public bool $gender;
  public bool $hispanc_or_latino;
  public bool $us_citizen;
  public DateTime $date_of_birth;
  public string $phone;
  public Role $role;
  public Race $race;


  public function save()
  {
    $db = openConnection();
    $stmt = $db->prepare("INSERT INTO user (email, password, role_id, race_id) VALUES (?, ?, 1, 1)");
    $stmt->bind_param("ss", $this->email, $this->password);
    $stmt->execute();
    $stmt->close();
    $db->close();
  }



  public static function find($id)
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM user WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    $db->close();
    return User::fromResult($user);
  }

  public static function all()
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM user");
    $stmt->execute();
    $result = $stmt->get_result();
    $users = [];
    while ($user = $result->fetch_assoc()) {
      $users[] = User::fromResult($user);
    }
    $stmt->close();
    $db->close();
    return $users;
  }

  public static function fromResult($result)
  {
    $user = new User();
    $user->id = $result['id'];
    $user->email = $result['email'];
    $user->password = $result['password'];
    $user->first_name = $result['first_name'];
    $user->last_name = $result['last_name'];
    $user->gender = $result['gender'];
    $user->hispanc_or_latino = $result['hispanc_or_latino'];
    $user->us_citizen = $result['us_citizen'];
    $user->date_of_birth = $result['date_of_birth'];
    $user->phone = $result['phone'];

    $user->role = Role::find($result['role_id']);
    $user->race = Race::find($result['race_id']);

    return $user;
  }
}
