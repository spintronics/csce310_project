<?
// Tanya Trujillo
namespace App;

use DateTime;

require_once __DIR__ . '/../util.php';

use App\Exception;


enum Semester: string
{
  case Fall = "Fall";
  case Spring = "Spring";
  case Summer = "Summer";
  case Winter = "Winter";
}
class Class_Enrollment
{
  public ?int $ce_num = 0;
  public ?string $status = "";
  public ?Semester $semester = Semester::Fall;
  public ?int $year = 0;
  public ?int $class_id = 0;
  public ?int $UIN = 123456789;
  public ?string $class_name = "";

  public Student $student;

  public function create()
  {
    $db = openConnection();
    $semester = (string) $this->semester->value;
    $stmt = $db->prepare("INSERT INTO class_enrollment (status, semester, year, class_id, UIN) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiii", $this->status, $semester, $this->year, $this->class_id, $this->UIN);
    $stmt->execute();
    $stmt->close();
    $db->close();
  }

  public function update()
  {
    $db = openConnection();
    $semester = (string) $this->semester->value;
    $stmt = $db->prepare("UPDATE class_enrollment SET status=?, semester=?, year=?, class_id=?, UIN=? WHERE ce_num=?");
    $stmt->bind_param("ssiiii", $this->status, $semester, $this->year, $this->class_id, $this->UIN, $this->ce_num);
    $stmt->execute();
    $stmt->close();
    $db->close();
  }

  public function delete()
  {
    $db = openConnection();
    $stmt = $db->prepare("DELETE FROM class_enrollment WHERE ce_num=?");
    $stmt->bind_param("i", $this->ce_num);
    $success = $stmt->execute();

    if (!$success) {
      throw new \Exception($stmt->error);
    }
    $stmt->close();
    $db->close();
  }

  public function save()
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM class_enrollment WHERE ce_num = ?");
    $stmt->bind_param("i", $this->ce_num);
    $stmt->execute();
    $result = $stmt->get_result();
    $class_enrollment = $result->fetch_assoc();
    $stmt->close();
    $db->close();
    if ($class_enrollment) {
      $this->update();
    } else {
      $this->create();
    }
  }

  public static function find($ce_num)
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM class_enrollment WHERE ce_num = ?");
    $stmt->bind_param("i", $ce_num);
    $stmt->execute();
    $result = $stmt->get_result();
    $class_enrollment = $result->fetch_assoc();
    $stmt->close();
    $db->close();
    if (!$class_enrollment) {
      return null;
    }
    return Class_Enrollment::fromResult($class_enrollment);
  }

  public static function allByUIN($UIN)
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM course_enrollments where UIN= ? ORDER BY year DESC");
    $stmt->execute();
    $result = $stmt->get_result();
    $course_enrollent = [];
    while ($course_enrollent = $result->fetch_assoc()) {
      $course_enrollents[] = Course_Enrollment::fromResult($course_enrollent);
    }
    $stmt->close();
    $db->close();
    return $course_enrollents;
  }

  public static function getClassEnrollmentView($UIN)
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM class_enrollment_view where UIN= ? ORDER BY year DESC");
    $stmt->bind_param("i", $UIN); // Bind the $UIN parameter to the placeholder
    $stmt->execute();
    $result = $stmt->get_result();
    $class_enrollment_views = [];
    while ($class_enrollment_view = $result->fetch_assoc()) {
      if(!$class_enrollment_view) {
        return null;
      }
      $class_enrollment_views[] = Class_Enrollment::fromResultView($class_enrollment_view);
    }
    $stmt->close();
    $db->close();
    return $class_enrollment_views;
  }

  public static function fromResultView($result)
  {
    $class_enrollment = new Class_Enrollment();
    $class_enrollment->ce_num = $result['ce_num'];
    $class_enrollment->status = $result['status'];
    $class_enrollment->semester = Semester::from($result['semester']);
    $class_enrollment->year = $result['year'];
    $class_enrollment->class_id = $result['class_id'];
    $class_enrollment->UIN = $result['UIN'];
    $class_enrollment->class_name = $result['class_name'];

    return $class_enrollment;
  }

  public static function fromResult($result)
  {
    $class_enrollment = new Class_Enrollment();
    $class_enrollment->ce_num = $result['ce_num'];
    $class_enrollment->status = $result['status'];
    $class_enrollment->semester = Semester::from($result['semester']);
    $class_enrollment->year = $result['year'];
    $class_enrollment->class_id = $result['class_id'];
    $class_enrollment->UIN = $result['UIN'];

    return $class_enrollment;
  }
}