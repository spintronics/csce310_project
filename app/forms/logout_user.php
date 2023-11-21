<?
// Kevin Brown
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../util.php';
App\User::clearSession();

App\redirect("/pages/login.php");
