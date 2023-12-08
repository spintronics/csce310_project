<?
// Shaz Maradya
namespace App;
require_once __DIR__ . '/../models/student.php';


class Program
{
    public int $program_num = 0;
    public string $name = "";
    public string $description = "";


    public function create()
    {
      $db = openConnection();
      $stmt = $db->prepare("INSERT INTO programs (name,description) VALUES (?,?)");
      $stmt->bind_param("ss", $this->name, $this->description);
      $stmt->execute();
      $stmt->close();
      $db->close();
    }

    public function update()
    {
      $db = openConnection();
      $stmt = $db->prepare("UPDATE programs SET name=?,description=? WHERE program_num=?");
      $stmt->bind_param("ssi", $this->name,$this->description,$this->program_num);
      $success = $stmt->execute();
      if (!$success) {
        throw new \Exception($stmt->error);
      }
      $stmt->close();
      $db->close();
    }

    public function delete()
    {
      $db = openConnection();

      # delete documentation
      $stmt = $db->prepare("DELETE FROM documentation WHERE app_num in (Select app_num from application where program_num=?)");
      $stmt->bind_param("i", $this->program_num);
      $success = $stmt->execute();

      # delete application
      $stmt = $db->prepare("DELETE FROM application WHERE program_num=?");
      $stmt->bind_param("i", $this->program_num);
      $success = $stmt->execute();
      
      # delete track
      $stmt = $db->prepare("DELETE FROM track WHERE program_num=?");
      $stmt->bind_param("i", $this->program_num);
      $success = $stmt->execute();

      # delete event tracking
      $stmt = $db->prepare("DELETE FROM event_tracking WHERE event_event_id in (Select event_id from event where program_num=?)");
      $stmt->bind_param("i", $this->program_num);
      $success = $stmt->execute();
      
      # delete event
      $stmt = $db->prepare("DELETE FROM event WHERE program_num=?");
      $stmt->bind_param("i", $this->program_num);
      $success = $stmt->execute();

      # delete program
  
      $stmt = $db->prepare("DELETE FROM programs WHERE program_num=?");
      $stmt->bind_param("i", $this->program_num);
      $success = $stmt->execute();
  
      if (!$success) {
        throw new \Exception($stmt->error);
      }
      $stmt->close();
      $db->close();
    }

    public static function fromResult($result)
    {
      $program = new Program();
      $program->program_num  = $result['program_num'];
      $program->name = $result['name'];
      $program->description = $result['description'];
  
      return $program;
    }

    public static function all()
    {
      $db = openConnection();
      $stmt = null;
      $stmt = $db->prepare("SELECT * FROM programs");
      $stmt->execute();
      $result = $stmt->get_result();
      $programs = [];
      while ($program = $result->fetch_assoc()) {
        $programs[] = Program::fromResult($program);
      }
      $stmt->close();
      $db->close();
      return $programs;
    }

    public static function get($id)
    {

      $db = openConnection();
      $stmt = $db->prepare("SELECT * from programs WHERE program_num=?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $result = $stmt->get_result();
      $program = $result->fetch_assoc();
      if(!$program){
        return null;
      }
      $program = Program::fromResult($program);
      $stmt->close();
      $db->close();
      return $program;
    }

    public static function nameMapping(){
      $programs = Program::all();
      $mapping = array();
      foreach($programs as $program){
        $mapping[$program->program_num] = $program->name;
      }

      return $mapping;
    }

    public static function members($id){
      $db = openConnection();
      $stmt = $db->prepare("SELECT college_student.* from track join college_student on track.UIN = college_student.UIN WHERE track.program_num = ?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $result = $stmt->get_result();
      $members = [];
      while ($member = $result->fetch_assoc()) {
        $members[] = Student::fromResult($member);
      }
      $stmt->close();
      $db->close();
      return $members;
    }

}