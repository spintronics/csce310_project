<?
// Tanya Trujillo
namespace App;

require_once __DIR__ . '/../util.php';

use App\Exception;

enum CertSemester: string
{
  case Fall = "Fall";
  case Spring = "Spring";
  case Summer = "Summer";
  case Winter = "Winter";
}

class Cert_Enrollment
{
  public ?int $cert_num = 0;
  public ?string $status = "";
  public ?string $training_status = "";
  public ?CertSemester $semester = CertSemester::Fall;
  public ?int $year = 0;
  public ?int $UIN = 123456789;
    public ?int $cert_id = 0;
    public ?string $cert_name = "";
    public ?string $cert_description = "";
    public ?string $level = "";
    public ?int $program_num = 0;
    public ?string $program_name = "";

  public function create()
{
    $db = openConnection();
    // create certification first
    $stmt = $db->prepare("INSERT INTO certification (name, description, level) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $this->cert_name, $this->cert_description, $this->level);
    $stmt->execute();

    // get the id of the certification we just created
    $cert_id = $db->insert_id;
    $semester = (string) $this->semester->value;
    $stmt = $db->prepare("INSERT INTO cert_enrollment (status, training_status, semester, year, cert_id, UIN, program_num) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiiii", $this->status, $this->training_status, $semester, $this->year, $cert_id, $this->UIN, $this->program_num);
    $stmt->execute();
    $stmt->close();
    $db->close();
}

  public function update()
  {
    $db = openConnection();
    $stmt = $db->prepare("UPDATE certification SET name=?, description=?, level=? WHERE cert_id=?");
    $stmt->bind_param("ssii", $this->cert_name, $this->cert_description, $this->level, $this->cert_id);
    $stmt->execute();
    
    $semester = (string) $this->semester->value;
    $stmt = $db->prepare("UPDATE cert_enrollment SET status=?, training_status=?, semester=?, year=?, cert_id=?, UIN=?, program_num=? WHERE cert_num=?");
    $stmt->bind_param("sssiiiii", $this->status, $this->training_status, $semester, $this->year, $this->cert_id, $this->UIN, $this->program_num, $this->cert_num);
    $stmt->execute();
    $stmt->close();
    $db->close();
  }

  public function save()
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM cert_enrollment WHERE cert_num = ?");
    $stmt->bind_param("i", $this->cert_num);
    $stmt->execute();
    $result = $stmt->get_result();
    $cert_enrollment = $result->fetch_assoc();
    $stmt->close();
    $db->close();
    if ($cert_enrollment) {
      $this->update();
    } else {
      $this->create();
    }
  }

  public function delete()
  {
    $db = openConnection();
    $stmt = $db->prepare("DELETE FROM cert_enrollment WHERE cert_num=?");
    $stmt->bind_param("i", $this->cert_num);
    $success = $stmt->execute();

    if (!$success) {
      throw new \Exception($stmt->error);
    }
    $stmt->close();
    $db->close();
  }

  public static function find($cert_num)
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM cert_enrollment WHERE cert_num = ?");
    $stmt->bind_param("i", $cert_num);
    $stmt->execute();
    $result = $stmt->get_result();
    $cert_enrollment = $result->fetch_assoc();
    $stmt->close();
    $db->close();
    if (!$cert_enrollment) {
      return null;
    }
    return Cert_Enrollment::fromResult($cert_enrollment);
  }

  public static function allByUIN($UIN)
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM cert_enrollments where UIN= ? ORDER BY year DESC");
    $stmt->execute();
    $result = $stmt->get_result();
    $cert_enrollments = [];
    while ($course_enrollment = $result->fetch_assoc()) {
      $cert_enrollments[] = Cert_Enrollment::fromResult($cert_enrollment);
    }
    $stmt->close();
    $db->close();
    return $cert_enrollments;
  }

  public static function getCertEnrollmentView($UIN)
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM cert_enrollment_view where UIN= ? ORDER BY year DESC");
    $stmt->bind_param("i", $UIN); // Bind the $UIN parameter to the placeholder
    $stmt->execute();
    $result = $stmt->get_result();
    $cert_enrollment_views = [];
    while ($cert_enrollment_view = $result->fetch_assoc()) {
      if(!$cert_enrollment_view) {
        return null;
      }
      $cert_enrollment_views[] = Cert_Enrollment::fromResultView($cert_enrollment_view);
    }
    $stmt->close();
    $db->close();
    return $cert_enrollment_views;
  }

  public static function fromResultView($result)
  {
    $cert_enrollment = new Cert_Enrollment();
    $cert_enrollment->cert_num = $result['cert_num'];
    $cert_enrollment->status = $result['status'];
    $cert_enrollment->year = $result['year'];
    $cert_enrollment->training_status = $result['training_status'];
    $cert_enrollment->semester = CertSemester::from($result['semester']);
    $cert_enrollment->cert_id = $result['cert_id'];
    $cert_enrollment->UIN = $result['UIN'];
    $cert_enrollment->cert_name = $result['cert_name'];
    $cert_enrollment->cert_description = $result['cert_description'];
    $cert_enrollment->level = $result['level'];
    $cert_enrollment->program_num = $result['program_num'];
    $cert_enrollment->program_name = $result['program_name'];

    return $cert_enrollment;
  }

  public static function fromResult($result)
  {
    $cert_enrollment = new Cert_Enrollment();
    $cert_enrollment->cert_num = $result['cert_num'];
    $cert_enrollment->status = $result['status'];
    $cert_enrollment->training_status = $result['training_status'];
    $cert_enrollment->year = $result['year'];
    $cert_enrollment->UIN = $result['UIN'];
    $cert_enrollment->cert_id = $result['cert_id'];
    $cert_enrollment->program_num = $result['program_num'];
    $cert_enrollment->semester = CertSemester::from($result['semester']);

    return $cert_enrollment;
  }
}