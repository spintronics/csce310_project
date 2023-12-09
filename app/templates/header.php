<?
// Kevin Brown
require_once __DIR__ . '/../models/user.php';


// session_start();

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$user = App\User::fromSession();

if ($user && !$user->active_account) {
  App\User::clearSession();
  $user = null;
  App\redirect("/pages/login.php?error=Account is inactive");
}

?>

<head>
  <meta charset="utf-8">
  <title>CSCE310 project</title>
  <link rel="stylesheet" href="/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" integrity="sha512-NhSC1YmyruXifcj/KFRWoC561YpHpc5Jtzgvbuzx5VozKpWvQ+4nXhPdFgmx8xqexRcpAglTj9sIBWINXa8x5w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>
<header>
  <h1>CSCE310 project</h1>
  <nav>
    <ul>
      <li><a href="/pages/home.php">Home</a></li>
      <?
      if (App\User::loggedIn()) {
      ?>
        <li><a href='/forms/logout_user.php'>Logout</a></li>
        <li><a href="/pages/user.php">User</a></li>
        <? if ($user->isAdmin()) { ?>
          <li><a href='/pages/admin_users.php'>Admin Users</a></li>
          <li><a href='/pages/admin_programs.php'>Programs</a></li>
          <li><a href='/pages/event.php'>Events</a></li>
          <? } else { ?>
            <li><a href='/pages/applications.php'>Applications</a></li>
            <li><a href='/pages/event.php'>Events</a></li>
            <li><a href='/pages/user_docx.php'>Documents</a></li>
        <? } ?>
      <? } else { ?>
        <li><a href='/pages/register.php'>Register</a></li>
        <li><a href='/pages/login.php'>Login</a></li>
      <? } ?>
    </ul>
  </nav>
</header>
<? if (isset($_GET['error'])) { ?>
  <div class="error">
    <?= $_GET['error'] ?>
  </div>
<? } ?>