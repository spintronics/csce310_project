<?php
// Kevin Brown
namespace App;

use mysqli;

function openConnection()
{
  return new mysqli("db", "root", "admin", "mydb", "3306");
}

function redirect($page)
{
  header("Location: " . $page);
  exit;
}

function redirectIfNotAuthenticated()
{
  if (!isset($_COOKIE['user_uin'])) {
    redirect("/pages/login.php");
  }
}

function valueOr($resource, $key, $default)
{
  return isset($resource[$key]) ? $resource[$key] : $default;
}

function getOr($key, $default)
{
  return valueOr($_GET, $key, $default);
}

function postOr($key, $default)
{
  return valueOr($_POST, $key, $default);
}
