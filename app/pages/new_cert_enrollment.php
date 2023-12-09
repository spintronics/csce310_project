<?php

// Tanya Trujillo

require_once __DIR__ . '/../models/cert_enrollment.php';
require_once __DIR__ . '/../models/program.php';

use App\Cert_Enrollment;
use App\CertSemester;

include __DIR__ . '/../templates/header.php';
?>

<form method="post" action="/forms/create_cert_enrollment.php">
  <h1>Create Certificate Enrollment</h1>
  <div>
    <label for="UIN">UIN:</label>
    <input type="text" name="UIN" placeholder="UIN" required="required" />
  </div>
    <div>
        <label for="cert">Certificate:</label>
        <input type="text" name="cert_name" placeholder="Certificate Name" required="required" />  
    </div>
    <div>
        <label for="cert_description">Description:</label>
        <input type="text" name="cert_description" placeholder="Description" required="required" />
    </div>
    <div>
        <label for="level">Level:</label>
        <input type="text" name="level" placeholder="Level" required="required" />
    </div>
        
    <div>
        <label for="year">Year:</label>
        <input type="text" name="year" placeholder="Year" required="required" />
    </div>
    <div>
        <label for="status">Status:</label>
        <input type="text" name="status" placeholder="Status" required="required" />
    </div>
    <div>
        <label for="training_status">Training Status:</label>
        <input type="text" name="training_status" placeholder="Training Status" required="required" />
    </div>
    <div> 
    <label for="semester">Semester:</label>
    <select name="semester" required>
    <? foreach (App\CertSemester::cases() as $semester) { ?>
          <option value="<?= $semester->value ?>" ><?= $semester->value ?></option>
    <? } ?>
    </select>
    </div>
    <div>
    <label for="name">Program Name:</label><br>
    <select id="name" name="program_num">
        <? foreach (App\Program::all() as $program) { ?>
            <option value="<?= $program->program_num ?>"><?= $program->name ?></option>
        <? } ?>
    </select>
    </div>

  <input type="submit" value="Add" onsubmit="" />
</form>