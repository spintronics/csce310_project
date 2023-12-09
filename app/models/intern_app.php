<?
// Tanya Trujillo
namespace App;

use DateTime;

require_once __DIR__ . '/../util.php';

use App\Exception;


class Intern_App
{
  public ?int $ia_num = 0;
  public ?string $status = "";
  public ?int $year = 0;
  public ?int $intern_id = 0;
  public ?int $UIN = 123456789;
    public ?string $intern_name = "";
    public ?string $intern_description = "";
    public ?int $is_gov = 0;

  public function create()
{
    $db = openConnection();
    // create internship first
    $stmt = $db->prepare("INSERT INTO internship (name, description, is_gov) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $this->intern_name, $this->intern_description, $this->is_gov);
    $stmt->execute();

    // get the id of the internship we just created
    $intern_id = $db->insert_id;
    $stmt = $db->prepare("INSERT INTO intern_app (status, year, intern_id, UIN) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siii", $this->status, $this->year, $intern_id, $this->UIN);
    $stmt->execute();
    $stmt->close();
    $db->close();
}

  public function update()
  {
    $db = openConnection();
    $stmt = $db->prepare("UPDATE internship SET name=?, description=?, is_gov=? WHERE intern_id=?");
    $stmt->bind_param("ssii", $this->intern_name, $this->intern_description, $this->is_gov, $this->intern_id);
    $stmt->execute();
    
    $stmt = $db->prepare("UPDATE intern_app SET status=?, year=?, intern_id=?, UIN=? WHERE ia_num=?");
    $stmt->bind_param("siiii", $this->status, $this->year, $this->intern_id, $this->UIN, $this->ia_num);
    $stmt->execute();
    $stmt->close();
    $db->close();
  }

  public function save()
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM intern_app WHERE ia_num = ?");
    $stmt->bind_param("i", $this->ia_num);
    $stmt->execute();
    $result = $stmt->get_result();
    $intern_app = $result->fetch_assoc();
    $stmt->close();
    $db->close();
    if ($intern_app) {
      $this->update();
    } else {
      $this->create();
    }
  }

  public function delete()
  {
    $db = openConnection();
    $stmt = $db->prepare("DELETE FROM intern_app WHERE ia_num=?");
    $stmt->bind_param("i", $this->ia_num);
    $success = $stmt->execute();

    if (!$success) {
      throw new \Exception($stmt->error);
    }
    $stmt->close();
    $db->close();
  }

  public static function find($ia_num)
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM intern_app WHERE ia_num = ?");
    $stmt->bind_param("i", $ia_num);
    $stmt->execute();
    $result = $stmt->get_result();
    $intern_app = $result->fetch_assoc();
    $stmt->close();
    $db->close();
    if (!$intern_app) {
      return null;
    }
    return Intern_App::fromResult($intern_app);
  }

  public static function allByUIN($UIN)
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM class_enrollments where UIN= ? ORDER BY year DESC");
    $stmt->execute();
    $result = $stmt->get_result();
    $course_enrollent = [];
    while ($course_enrollent = $result->fetch_assoc()) {
      $course_enrollents[] = Intern_App::fromResult($course_enrollent);
    }
    $stmt->close();
    $db->close();
    return $course_enrollents;
  }

  public static function getInternshipAppView($UIN)
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM intern_app_with_internship_view where UIN= ? ORDER BY year DESC");
    $stmt->bind_param("i", $UIN); // Bind the $UIN parameter to the placeholder
    $stmt->execute();
    $result = $stmt->get_result();
    $intern_app_views = [];
    while ($intern_app_view = $result->fetch_assoc()) {
      if(!$intern_app_view) {
        return null;
      }
      $intern_app_views[] = Intern_App::fromResultView($intern_app_view);
    }
    $stmt->close();
    $db->close();
    return $intern_app_views;
  }

  public static function fromResultView($result)
  {
    $intern_app = new Intern_App();
    $intern_app->ia_num = $result['ia_num'];
    $intern_app->status = $result['status'];
    $intern_app->year = $result['year'];
    $intern_app->UIN = $result['UIN'];
    $intern_app->intern_id = $result['intern_id'];
    $intern_app->intern_name = $result['intern_name'];
    $intern_app->intern_description = $result['intern_description'];
    $intern_app->is_gov = $result['is_gov'];

    return $intern_app;
  }

  public static function fromResult($result)
  {
    $intern_app = new Intern_App();
    $intern_app->ia_num = $result['ia_num'];
    $intern_app->status = $result['status'];
    $intern_app->year = $result['year'];
    $intern_app->intern_id = $result['intern_id'];
    $intern_app->UIN = $result['UIN'];

    return $intern_app;
  }
}