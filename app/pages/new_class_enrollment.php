<?php

// Tanya Trujillo
require_once __DIR__ . '/../models/class_enrollment.php';
require_once __DIR__ . '/../models/class.php';

use App\Classes;
use App\ClassEnrollment;
use App\Semester;

$classes = Classes::all();

include __DIR__ . '/../templates/header.php';
?>

<form method="post" action="/forms/create_class_enrollment.php">
  <h1>Create New Class Enrollment</h1>
  <div>
    <label for="UIN">UIN</label>
    <input type="text" name="UIN" placeholder="UIN" required="required" />
  </div>
  <div>
    <label for="class">Class</label>
    <select id="name" name="class_id">
        <? foreach ($classes as $class) { ?>
            <option value="<?= $class->class_id ?>"><?= $class->name ?></option>
        <? } ?>
    </select>
  </div>
  <div>
    <label for="year">Year</label>
    <input type="text" name="year" placeholder="year" required="required" />
    </div>
    <div> 
    <label for="semester">Semester</label>
    <select name="semester" required>
    <? foreach (Semester::cases() as $semester_type) { ?>
          <option value="<?= $semester_type->value ?>" ><?= $semester_type->value ?></option>
        <? } ?>
    </select>
    </div>
    <div>
    <label for="status">Status</label>
    <input type="text" name="status" placeholder="status" required="required" />
    </div>


  <input type="submit" value="Add" onsubmit="" />
</form>