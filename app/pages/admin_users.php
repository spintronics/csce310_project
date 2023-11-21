<?

require_once __DIR__ . '/../util.php';
require_once __DIR__ . '/../models/user.php';

$sort = App\getOr('sort', 'UIN');

$users = App\User::all($sort);

include __DIR__ . '/../templates/header.php';

?>

<h1>Users</h1>
<div class="table-responsive user-table">
  <table class="table" style="table-layout: fixed;">
    <thead>
      <tr>
        <th><a href="/pages/admin_users.php?sort=UIN">UIN</a></th>
        <th><a href="/pages/admin_users.php?sort=first_name">First Name</a></th>
        <th><a href="/pages/admin_users.php?sort=m_initial">M Initial</a></th>
        <th><a href="/pages/admin_users.php?sort=last_name">Last Name</a></th>
        <th><a href="/pages/admin_users.php?sort=username">Username</a></th>
        <th><a href="/pages/admin_users.php?sort=password">Password</a></th>
        <th><a href="/pages/admin_users.php?sort=user_type">User Type</a></th>
        <th><a href="/pages/admin_users.php?sort=email">Email</a></th>
        <th><a href="/pages/admin_users.php?sort=discord_name">Discord Name</a></th>
        <th><a href="/pages/admin_users.php?sort=acitve_account">Active Account</a></th>
        <th>Actions</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <? foreach ($users as $user) : ?>
        <form action="/forms/admin_user_action.php" method="post">
          <tr>
            <td>
              <input type="number" name="UIN" value="<?= $user->UIN ?>" minlength="9" />
            </td>
            <td>
              <input type="text" name="first_name" value="<?= $user->first_name ?>" />
            </td>
            <td>
              <input type="text" name="m_initial" value="<?= $user->m_initial ?>" />
            </td>
            <td>
              <input type="text" name="last_name" value="<?= $user->last_name ?>" />
            </td>
            <td>
              <input type="text" name="username" value="<?= $user->username ?>" />
            </td>
            <td>
              <input type="text" name="password" value="<?= $user->password ?>" />
            </td>
            <td>
              <select name="user_type">
                <? foreach (App\UserType::cases() as $user_type) { ?>
                  <option value="<?= $user_type->value ?>" <? if ($user->user_type == $user_type) echo "selected"; ?>><?= $user_type->value ?></option>
                <? } ?>
              </select>
            </td>
            <td>
              <input type="text" name="email" value="<?= $user->email ?>" />
            </td>
            <td>
              <input type="text" name="discord_name" value="<?= $user->discord_name ?>" />
            </td>
            <td>
              <input type="checkbox" name="active_account" <? if ($user->active_account) echo "checked"; ?> />
            </td>
            <td>
              <select name="action">
                <option value="update">Update</option>
                <option value="deactivate">Deactivate</option>
                <option value="activate">Activate</option>
                <option value="delete">Delete</option>
              </select>
            </td>
            <td>
              <input type="submit" value="Submit" onsubmit="" />
            </td>
          </tr>
        </form>
      <? endforeach ?>
    </tbody>
  </table>
</div>