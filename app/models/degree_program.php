<?
// Kevin Brown
namespace App;

require_once __DIR__ . '/../util.php';

class DegreeProgram
{
  public int $id;
  public string $name;

  public function save()
  {
    $db = openConnection();
    $stmt = $db->prepare("INSERT INTO degree_program (name) VALUES (?)");
    $stmt->bind_param("s", $this->name);
    $stmt->execute();
    $stmt->close();
    $db->close();
  }

  public static function find(int $id)
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM `degree_program` WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $degree_program = $result->fetch_assoc();
    $stmt->close();
    $db->close();
    return DegreeProgram::fromResult($degree_program);
  }

  public static function all()
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM `degree_program`");
    $stmt->execute();
    $result = $stmt->get_result();
    $degree_programs = [];
    while ($degree_program = $result->fetch_assoc()) {
      $degree_programs[] = DegreeProgram::fromResult($degree_program);
    }
    $stmt->close();
    $db->close();
    return $degree_programs;
  }

  public static function fromResult($result)
  {
    $degree_program = new DegreeProgram();
    $degree_program->id = $result['id'];
    $degree_program->name = $result['name'];
    return $degree_program;
  }
}
