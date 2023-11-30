<?
// Shaz Maradya
require_once __DIR__ . '/../util.php';
require_once __DIR__ . '/../models/application.php';

include __DIR__ . '/../templates/header.php';

$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id) {
    App\redirect("/pages/admin_programs.php");
    exit;
}

$app = App\Application::get($id);

?>


<div style="margin-left: 10px;">
    <div >
        <span>Student UIN: <?= $app->UIN ?></span><br/>
        <span>Program Name: <?= $app->UIN ?></span>
    </div><br/>

    <h4>Short Answer Questions</h4> <br/>
    <h6>Are you currently enrolled in other uncompleted certifications sponsored by the Cybersecurity Center?</h6>
    <p><?= $app->uncom_cert ?></p><br/>

    <h6>Have you completed anycybersecurity industry certifications via the Cybersecurity Center?</h6>
    <p><?= $app->com_cert ?></p><br/>

    <h6>Purpose Statement</h6>
    <p><?= $app->purpose_statement ?></p><br/>

    <form method="post" action="/forms/admin_application_action.php">
        <h5>Action Application</h5>
        <select name="action">
            <option value="accept">Accept</option>
            <option value="reject">Reject</option>
        </select>
        <input type="hidden" name="app_num" value="<?= $app->app_num ?>"/>
        <input type="submit" value="Submit" onsubmit="" />
    </form>

</div>

