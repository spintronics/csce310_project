<?php

// Tanya Trujillo

require_once __DIR__ . '/../models/internship.php';
require_once __DIR__ . '/../models/intern_app.php';

use App\Internship;
use App\Intern_App;

include __DIR__ . '/../templates/header.php';
?>

<form method="post" action="/forms/create_intern_app.php">
  <h1>Create New Internship Application</h1>
  <div>
    <label for="UIN">UIN</label>
    <input type="text" name="UIN" placeholder="UIN" required="required" />
  </div>
  <div>
    <label for="internship">Internship</label>
    <input type="text" name="intern_name" placeholder="Internship Name" required="required" />
  </div>
  <div>
    <label for="intern_description">Description</label>
    <input type="text" name="intern_description" placeholder="Description" required="required" />
</div>
    <div>
    <label for="is_gov">Is Government Affiliated</label>
    <input type="checkbox" name="is_gov" value="1" />
    </div>
  <div>
    <label for="year">Year</label>
    <input type="text" name="year" placeholder="Year" required="required" />
    </div>
    <div>
    <label for="status">Status</label>
    <input type="text" name="status" placeholder="Status" required="required" />
    </div>


  <input type="submit" value="Add" onsubmit="" />
</form>