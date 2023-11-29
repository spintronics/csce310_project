<?
// Shaz Maradya
namespace App;

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

}