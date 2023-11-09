<?
// Kevin Brown
namespace App;

use DateTime;

class Student
{
  public int $id;
  public string $uin;
  public bool $first_generation_student;
  public DateTime $expected_graduation;
  public float $gpa;
  public User $user;
  public StudentClassification $student_classification;

  public function save()
  {
    $db = openConnection();
    $stmt = $db->prepare("INSERT INTO student (uin, first_generation_student, expected_graduation, gpa, user_id, student_classification_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsiii", $this->uin, $this->first_generation_student, $this->expected_graduation, $this->gpa, $this->user->id, $this->student_classification->id);
    $stmt->execute();
    $stmt->close();
    $db->close();
  }

  public static function find($id)
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM student WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();
    $db->close();
    return Student::fromResult($student);
  }

  public static function all()
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM student");
    $stmt->execute();
    $result = $stmt->get_result();
    $students = [];
    while ($student = $result->fetch_assoc()) {
      $students[] = Student::fromResult($student);
    }
    $stmt->close();
    $db->close();
    return $students;
  }

  public static function fromResult($result)
  {
    $student = new Student();
    $student->id = $result['id'];
    $student->uin = $result['uin'];
    $student->first_generation_student = $result['first_generation_student'];
    $student->expected_graduation = $result['expected_graduation'];
    $student->gpa = $result['gpa'];
    $student->user = User::find($result['user_id']);
    $student->student_classification = StudentClassification::find($result['student_classification_id']);
    return $student;
  }
}
