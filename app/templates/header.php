<?

require_once __DIR__ . '/../models/user.php';

session_start();

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
        echo "<li><a href='/forms/logout_user.php'>Logout</a></li>";
      } else {
        echo "<li><a href='/pages/register.php'>Register</a</li>";
        echo "<li><a href='/pages/login.php'>Login</a></li>";
      }
      ?>
      <li><a href="/pages/user.php">User</a></li>
    </ul>
  </nav>
</header>
<? if (isset($_GET['error'])) { ?>
  <div class="error">
    <?= $_GET['error'] ?>
  </div>
<? } ?>