<?php
// Kevin Brown

namespace App;

use DateTime;

require_once __DIR__ . '/../util.php';

use App\Exception;

enum UserType: string
{
  case Student = "student";
  case Admin = "admin";
}

class User
{
  public ?int $UIN = 123456789;
  public ?string $first_name = "";
  public ?string $m_initial = "";
  public ?string $last_name = "";
  public ?string $username = "";
  public ?string $password = "";
  public ?UserType $user_type = UserType::Student;
  public ?string $email = "";
  public ?string $discord_name = "";
  public ?bool $active_account = true;

  public function verifyPassword($password)
  {
    return $password == $this->password;
  }

  public function update()
  {
    $db = openConnection();
    $user_type = (string) $this->user_type->value;
    $stmt = $db->prepare("UPDATE user SET first_name=?, m_initial=?, last_name=?, username=?, `password`=?, user_type=?, email=?, discord_name=?, active_account=? WHERE UIN=?");
    $stmt->bind_param("ssssssssii", $this->first_name, $this->m_initial, $this->last_name, $this->username, $this->password, $user_type, $this->email, $this->discord_name, $this->active_account, $this->UIN);
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
    $stmt = $db->prepare("DELETE FROM college_student WHERE UIN=?");
    $stmt->bind_param("i", $this->UIN);
    $success = $stmt->execute();

    $stmt = $db->prepare("DELETE FROM user WHERE UIN=?");
    $stmt->bind_param("i", $this->UIN);
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
    $user_type = (string) $this->user_type->value;
    $active_account = true;
    $stmt = $db->prepare("INSERT INTO user (UIN, first_name, m_initial, last_name, username, `password`, user_type, email, discord_name, active_account) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssssi", $this->UIN, $this->first_name, $this->m_initial, $this->last_name, $this->username, $this->password, $user_type, $this->email, $this->discord_name, $active_account);
    $stmt->execute();
    $stmt->close();
    $db->close();
  }

  public function save()
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM user WHERE UIN = ?");
    $stmt->bind_param("i", $this->UIN);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    $db->close();
    if ($user) {
      $this->update();
    } else {
      $this->create();
    }
  }

  public static function count()
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT COUNT(*) FROM user");
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['COUNT(*)'];
    $stmt->close();
    $db->close();
    return $count;
  }

  public static function findBy($column, $value)
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM user WHERE $column = ? LIMIT 1");
    $stmt->bind_param("s", $value);
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

  public static function find($UIN)
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM user WHERE UIN = ? LIMIT 1");
    $stmt->bind_param("i", $UIN);
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

  public static function all($sort = 'UIN', $page = 1)
  {
    $db = openConnection();
    $stmt = null;
    $stmt = $db->prepare("SELECT * FROM user ORDER BY $sort LIMIT 10 OFFSET ?");
    $offset = ($page - 1) * 10;
    $stmt->bind_param("i", $offset);
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
    $user->UIN = $result['UIN'];
    $user->first_name = $result['first_name'];
    $user->m_initial = $result['m_initial'];
    $user->last_name = $result['last_name'];
    $user->username = $result['username'];
    $user->password = $result['password'];
    $user->email = $result['email'];
    $user->user_type = UserType::from($result['user_type']);
    $user->discord_name = $result['discord_name'];
    $user->active_account = $result['active_account'];

    return $user;
  }

  public static function fromSession()
  {
    $user_id = isset($_SESSION['user_uin']) ? $_SESSION['user_uin'] : (isset($_COOKIE['user_uin']) ? $_COOKIE['user_uin'] : null);
    if (!$user_id) {
      return null;
    }
    return User::find($user_id);
  }

  public function saveSession()
  {
    if (!$this->UIN) {
      return;
    }
    $_SESSION['user_uin'] = $this->UIN;
    // set the cookie to expire in 30 days
    setcookie('user_uin', $this->UIN, time() + 60 * 60 * 24 * 30, "/");
  }

  public static function clearSession()
  {
    // clear the session and cookie
    if (isset($_SESSION['user_uin'])) {
      unset($_SESSION['user_uin']);
    }

    if (isset($_COOKIE['user_uin'])) {
      unset($_COOKIE['user_uin']);
      setcookie('user_uin', '', time() - 3600, '/');
    }
  }

  public static function loggedIn()
  {
    return isset($_SESSION['user_uin']) || isset($_COOKIE['user_uin']);
  }

  public function isAdmin()
  {
    return $this->user_type == UserType::Admin;
  }

  public static function createRandom()
  {
    // create a random user using uuids
    $user = new User();
    $user->UIN = rand(1000000000, 9999999999);
    $user->first_name = uniqid();
    $user->m_initial = uniqid();
    $user->last_name = uniqid();
    $user->username = "test";
    $user->password = "test";
    $user->email = uniqid() . "@test.com";
    $user->user_type = UserType::Student;
    $user->discord_name = uniqid();
    $user->active_account = true;
    $user->create();
    return $user;
  }

  public static function pageCount()
  {
    $count = User::count();
    return ceil($count / 10);
  }

  public static function deactivatedUsers()
  {
    // use the inactive_accounts view to get all users with inactive accounts
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM `inactive_accounts`");
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
}
