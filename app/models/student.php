<?
// Kevin Brown
namespace App;

use DateTime;

enum Gender: string
{
  case Male = "male";
  case Female = "female";
  case Other = "other";
}

enum Race: string
{
  case AmericanIndianOrAlaskaNative = "american_indian_or_alaska_native";
  case Asian = "asian";
  case BlackOrAfricanAmerican = "black_or_african_american";
  case NativeHawaiianOrOtherPacificIslander = "native_hawaiian_or_other_pacific_islander";
  case White = "white";
  case Other = "other";
}

enum StudentClassification: string
{
  case Freshman = "freshman";
  case Sophomore = "sophomore";
  case Junior = "junior";
  case Senior = "senior";
  case Graduate = "graduate";
}
class Student
{
  public int $UIN = 123456789;
  public Gender $gender = Gender::Other;
  public bool $hispanic_latino = false;
  public Race $race = Race::Asian;
  public bool $us_citizen = true;
  public bool $first_generation = false;
  public DateTime $date_of_birth;
  public float $gpa = 0.0;
  public string $major = "";
  public string $minor_1 = "";
  public string $minor_2 = "";
  public DateTime $expected_graduation;
  public string $school = "";
  public StudentClassification $current_classification = StudentClassification::Freshman;
  public string $phone = "";

  function __construct()
  {
    $this->date_of_birth = new DateTime();
    $this->expected_graduation = new DateTime();
  }

  public User $user;

  public function create()
  {
    $db = openConnection();
    $gender = (string) $this->gender->value;
    $race = (string) $this->race->value;
    $current_classification = (string) $this->current_classification->value;
    $date_of_birth = $this->date_of_birth->format("Y-m-d");
    $expected_graduation = $this->expected_graduation->format("Y-m-d");
    $stmt = $db->prepare("INSERT INTO college_student (UIN, gender, hispanic_latino, race, us_citizen, first_generation, date_of_birth, gpa, major, minor_1, minor_2, expected_graduation, school, current_classification, phone) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param("isisiisisssssss", $this->UIN, $gender, $this->hispanic_latino, $race, $this->us_citizen, $this->first_generation, $date_of_birth, $this->gpa, $this->major, $this->minor_1, $this->minor_2, $expected_graduation, $this->school, $current_classification, $this->phone);
    $stmt->execute();
    $stmt->close();
    $db->close();
  }

  public function update()
  {
    $db = openConnection();
    $gender = (string) $this->gender->value;
    $race = (string) $this->race->value;
    $current_classification = (string) $this->current_classification->value;
    $date_of_birth = $this->date_of_birth->format("Y-m-d");
    $expected_graduation = $this->expected_graduation->format("Y-m-d");
    $stmt = $db->prepare("UPDATE college_student SET gender=?, hispanic_latino=?, race=?, us_citizen=?, first_generation=?, date_of_birth=?, gpa=?, major=?, minor_1=?, minor_2=?, expected_graduation=?, school=?, current_classification=?, phone=? WHERE UIN=?");
    $stmt->bind_param("sisiisssssssssi", $gender, $this->hispanic_latino, $race, $this->us_citizen, $this->first_generation, $date_of_birth, $this->gpa, $this->major, $this->minor_1, $this->minor_2, $expected_graduation, $this->school, $current_classification, $this->phone, $this->UIN);
    $stmt->execute();
    $stmt->close();
    $db->close();
  }

  public function save()
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM college_student WHERE UIN = ?");
    $stmt->bind_param("i", $this->UIN);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();
    $db->close();
    if ($student) {
      $this->update();
    } else {
      $this->create();
    }
  }

  public static function find($UIN)
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM college_student WHERE UIN = ?");
    $stmt->bind_param("i", $UIN);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();
    $db->close();
    if (!$student) {
      return null;
    }
    return Student::fromResult($student);
  }

  public static function all()
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM college_student");
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
    $student->UIN = $result["UIN"];
    $student->gender = Gender::from($result['gender']);
    $student->hispanic_latino = valueOr($result, 'hispanic_latino', false);
    $student->race = Race::from($result['race']);
    $student->us_citizen = valueOr($result, 'us_citizen', false);
    $student->first_generation = valueOr($result, 'first_generation', false);
    $student->date_of_birth = new DateTime($result['date_of_birth']);
    $student->gpa = $result['gpa'];
    $student->major = $result['major'];
    $student->minor_1 = $result['minor_1'];
    $student->minor_2 = $result['minor_2'];
    $student->expected_graduation = new DateTime($result['expected_graduation']);
    $student->school = $result['school'];
    $student->current_classification = StudentClassification::from($result['current_classification']);

    return $student;
  }
}
