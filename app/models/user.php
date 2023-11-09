<?php
// Kevin Brown

namespace App;

use DateTime;

require_once __DIR__ . '/../util.php';
require_once __DIR__ . '/role.php';
require_once __DIR__ . '/race.php';
require_once __DIR__ . '/gender.php';

use App\Exception;



class User
{
  public ?string $password;
  public ?string $email;
  public ?int $id;
  public ?string $first_name;
  public ?string $last_name;
  public ?bool $hispanic_or_latino;
  public ?bool $us_citizen;
  public ?string $date_of_birth;
  public ?string $phone;
  public Role $role;
  public Race $race;
  public Gender $gender;

  public function save()
  {
    $db = openConnection();
    // i am excluding email because it can violate the unique constraint
    $stmt = $db->prepare("UPDATE user SET `password`=?, first_name=?, last_name=?, hispanic_or_latino=?, us_citizen=?, date_of_birth=?, phone=?, role_id=?, race_id=?, gender_id=? WHERE id=?");
    $stmt->bind_param("sssiissiiii", $this->password, $this->first_name, $this->last_name, $this->hispanic_or_latino, $this->us_citizen, $this->date_of_birth, $this->phone, $this->role->id, $this->race->id, $this->gender->id, $this->id);
    $success = $stmt->execute();
    if (!$success) {
      throw new \Exception($stmt->error);
    }
    $stmt->close();
    $db->close();
  }


  public function create()
  {
    $db = openConnection();
    $stmt = $db->prepare("INSERT INTO user (email, password, role_id, race_id, gender_id) VALUES (?, ?, 1, 1, 1)");
    $stmt->bind_param("ss", $this->email, $this->password);
    $stmt->execute();
    $stmt->close();
    $db->close();
  }

  public static function findByEmail($email)
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM user WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    $db->close();
    if (!$user) {
      return null;
    }
    return User::fromResult($user);
  }

  public static function find($id)
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM user WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    $db->close();
    if (!$user) {
      return null;
    }
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
    $user->hispanic_or_latino = $result['hispanic_or_latino'];
    $user->us_citizen = $result['us_citizen'];
    $user->date_of_birth = $result['date_of_birth'];
    $user->phone = $result['phone'];

    $user->role = Role::find($result['role_id']);
    $user->race = Race::find($result['race_id']);
    $user->gender = Gender::find($result['gender_id']);

    return $user;
  }

  public static function fromSession()
  {
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : (isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : null);
    if (!$user_id) {
      return null;
    }
    return User::find($user_id);
  }

  public function saveSession()
  {
    if (!$this->id) {
      return;
    }
    $_SESSION['user_id'] = $this->id;
    setcookie('user_id', $this->id, time() + 60 * 60 * 24 * 30, "/");
  }

  public static function clearSession()
  {
    if (isset($_SESSION['user_id'])) {
      unset($_SESSION['user_id']);
    }

    if (isset($_COOKIE['user_id'])) {
      unset($_COOKIE['user_id']);
      setcookie('user_id', '', time() - 3600, '/');
    }
  }

  public static function loggedIn()
  {
    return isset($_SESSION['user_id']) || isset($_COOKIE['user_id']);
  }
}
