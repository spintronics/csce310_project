<?
// Tanya Trujillo
require_once __DIR__ . '/../util.php';
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/class_enrollment.php';
require_once __DIR__ . '/../models/intern_app.php';
require_once __DIR__ . '/../models/cert_enrollment.php';

use function App\redirect;

$current_user = App\User::find($_COOKIE['user_uin']);


include __DIR__ . '/../templates/header.php';
if ($current_user->isAdmin()) {
    if (!isset($_POST['student_uin'])) {
        $class_enrollments = App\Class_Enrollment::getClassEnrollmentView($current_user->UIN);
        $intern_apps = App\Intern_App::getInternshipAppView($current_user->UIN);
        $cert_enrollments = App\Cert_Enrollment::getCertEnrollmentView($current_user->UIN);
    } else {
        $student_uin = $_POST['student_uin'];
        $student = App\User::find($student_uin);
        $class_enrollments = App\Class_Enrollment::getClassEnrollmentView($student->UIN);
        $intern_apps = App\Intern_App::getInternshipAppView($student->UIN);
        $cert_enrollments = App\Cert_Enrollment::getCertEnrollmentView($student->UIN);
    }
} else {
    $class_enrollments = App\Class_Enrollment::getClassEnrollmentView($current_user->UIN);
    $intern_apps = App\Intern_App::getInternshipAppView($current_user->UIN);
    $cert_enrollments = App\Cert_Enrollment::getCertEnrollmentView($current_user->UIN);
}

// $current_user = App\User::find($_COOKIE['user_uin']);


// $class_enrollments = App\Class_Enrollment::getClassEnrollmentView($current_user->UIN);

// $intern_apps = App\Intern_App::getInternshipAppView($current_user->UIN);

// $cert_enrollments = App\Cert_Enrollment::getCertEnrollmentView($current_user->UIN);

?>

<h1>Program Tracking<h1>
<h2> Class Enrollments <h2>
<div class="table-responsive user-table">
  <table class="table" style="table-layout: fixed;">
    <thead>
      <tr>
        <th>Class Enrollment No</th>
        <th>UIN</th>
        <th>Class ID</th>
        <th>Class Name</th>
        <th>Year</th>
        <th>Semester</th>
        <th>Status</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <? foreach ($class_enrollments as $class_enrollment) : ?>
        <form action="/forms/class_enrollment_action.php" method="post">
          <tr>
            <td>
              <input type="number" name="ce_num" value="<?= $class_enrollment->ce_num ?>"/>
            </td>
            <td>
              <input type="number" name="UIN" value="<?= $class_enrollment->UIN ?>" minlength="9" />
            </td>
            <td>
              <input type="text" name="class_id" value="<?= $class_enrollment->class_id ?>" />
            </td>
            <td>
              <input type="text" name="class_name" value="<?= $class_enrollment->class_name ?>" />
            </td>
            <td>
              <input type="text" name="year" value="<?= $class_enrollment->year ?>" />
            </td>
            <td>
            <select name="semester">
                <? foreach (App\Semester::cases() as $semester) { ?>
                  <option value="<?= $semester->value ?>" <? if ($class_enrollment->semester == $semester) echo "selected"; ?>><?= $semester->value ?></option>
                <? } ?>
              </select>
            </td>
            <td>
              <input type="text" name="status" value="<?= $class_enrollment->status ?>" />
            </td>
            <td>
              <select name="action">
                <option value="update">Update</option>
                <option value="delete">Delete</option>
              </select>
            </td>
            <td>
              <input type="submit" value="Submit" onsubmit="" />
            </td>
          </tr>
        </form>
      <? endforeach ?>
      <td>
        <a a href='/pages/new_class_enrollment.php' ><input type="button" value="Add Class Enrollment"></a>
      </td>
    </tbody>
  </table>
</div>

<h2> Internships <h2>
<div class="table-responsive user-table">
  <table class="table" style="table-layout: fixed;">
    <thead>
      <tr>
        <th>Application No</th>
        <th>UIN</th>
        <th>Internship ID</th>
        <th>Internship Name</th>
        <th>Description</th>
        <th>Is Government Affiliated</th>
        <th>Year</th>
        <th>Status</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <? foreach ($intern_apps as $intern_app) : ?>
        <form action="/forms/intern_app_action.php" method="post">
          <tr>
            <td>
              <input type="number" name="ia_num" value="<?= $intern_app->ia_num ?>"/>
            </td>
            <td>
              <input type="number" name="UIN" value="<?= $intern_app->UIN ?>" minlength="9" />
            </td>
            <td>
              <input type="text" name="intern_id" value="<?= $intern_app->intern_id ?>" />
            </td>
            <td>
              <input type="text" name="intern_name" value="<?= $intern_app->intern_name ?>" />
            </td>
            <td>
              <input type="text" name="intern_description" value="<?= $intern_app->intern_description ?>" />
            </td>
            <td>
              <input type="text" name="is_gov" value="<?= $intern_app->is_gov ?>" />
            </td>
            <td>
              <input type="text" name="year" value="<?= $intern_app->year ?>" />
            </td>
            <td>
              <input type="text" name="status" value="<?= $intern_app->status ?>" />
            </td>
            <td>
              <select name="action">
                <option value="update">Update</option>
                <option value="delete">Delete</option>
              </select>
            </td>
            <td>
              <input type="submit" value="Submit" onsubmit="" />
            </td>
          </tr>
        </form>
      <? endforeach ?>
      <td>
        <a a href='/pages/new_intern_app.php' ><input type="button" value="Add Intern Application"></a>
      </td>
    </tbody>
  </table>
</div>

<h2> Certifications <h2>
<div class="table-responsive user-table">
  <table class="table" style="table-layout: fixed;">
    <thead>
      <tr>
        <th>Cert Enrollment No</th>
        <th>UIN</th>
        <th>Cert ID</th>
        <th>Cert Name</th>
        <th>Cert Description</th>
        <th>Level</th>
        <th>Program Number</th>
        <th>Program Name</th>
        <th>Year</th>
        <th>Semester</th>
        <th>Status</th>
        <th>Training Status</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <? foreach ($cert_enrollments as $cert_enrollment) : ?>
        <form action="/forms/cert_enrollment_action.php" method="post">
          <tr>
            <td>
              <input type="number" name="cert_num" value="<?= $cert_enrollment->cert_num ?>"/>
            </td>
            <td>
              <input type="number" name="UIN" value="<?= $cert_enrollment->UIN ?>" minlength="9" />
            </td>
            <td>
              <input type="text" name="cert_id" value="<?= $cert_enrollment->cert_id ?>" />
            </td>
            <td>
              <input type="text" name="cert_name" value="<?= $cert_enrollment->cert_name ?>" />
            </td>
            <td>
              <input type="text" name="cert_description" value="<?= $cert_enrollment->cert_description ?>" />
            </td>
            <td>
              <input type="text" name="level" value="<?= $cert_enrollment->level ?>" />
            </td>
            <td>
              <input type="text" name="program_num" value="<?= $cert_enrollment->program_num ?>" />
            </td>
            <td>
              <input type="text" name="program_name" value="<?= $cert_enrollment->program_name ?>" />
            </td>
            <td>
              <input type="text" name="year" value="<?= $cert_enrollment->year ?>" />
            </td>
            <td>
              <select name="semester">
                <? foreach (App\CertSemester::cases() as $semester) { ?>
                  <option value="<?= $semester->value ?>" <? if ($cert_enrollment->semester == $semester) echo "selected"; ?>><?= $semester->value ?></option>
                <? } ?>
              </select>
            </td>
            <td>
              <input type="text" name="status" value="<?= $cert_enrollment->status ?>" />
            </td>
            <td>
              <input type="text" name="training_status" value="<?= $cert_enrollment->training_status ?>" />
            </td>
            <td>
              <select name="action">
                <option value="update">Update</option>
                <option value="delete">Delete</option>
              </select>
            </td>
            <td>
              <input type="submit" value="Submit" onsubmit="" />
            </td>
          </tr>
        </form>
      <? endforeach ?>
      <td>
        <a a href='/pages/new_cert_enrollment.php' ><input type="button" value="Add Certification Enrollment"></a>
      </td>
    </tbody>
  </table>
</div>