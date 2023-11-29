<?
// Shaz Maradya
require_once __DIR__ . '/../util.php';
require_once __DIR__ . '/../models/program.php';

include __DIR__ . '/../templates/header.php';
$programs = App\Program::all();

?>

<h1>All Programs</h1>
<a href='/pages/new_program.php'>Create New Program</a>
<div class="table-responsive user-table">
  <table class="table" style="table-layout: fixed;">
    <thead>
      <tr>
        <th>Program Number</th>
        <th>Name</th>
        <th>Description</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <? foreach ($programs as $program) : ?>
          <tr>
            <td>
                <?= $program->program_num ?>
            </td>
            <td>
                <?= $program->name ?>
            </td>
            <td>
                <?= $program->description ?>
            </td>
            <td>
                <a href='/pages/programs.php?id=<?= $program->program_num ?>'>Edit Program</a>
            </td>
          </tr>
      <? endforeach ?>
    </tbody>
  </table>
</div>