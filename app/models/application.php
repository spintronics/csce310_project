<?
// Shaz Maradya
namespace App;

class Application
{
    public int $app_num = 0;
    public int $program_num = 0;
    public int $UIN = 123456789;
    public string $uncom_cert = "";
    public string $com_cert = "";
    public string $purpose_statement = "";

    public function create()
    {
      $db = openConnection();
      $stmt = $db->prepare("INSERT INTO application (program_num,UIN,uncom_cert,com_cert,purpose_statement) VALUES (?,?,?,?,?)");
      $stmt->bind_param("iisss", $this->program_num, $this->UIN,$this->uncom_cert,$this->com_cert,$this->purpose_statement);
      $stmt->execute();
      $stmt->close();
      $db->close();
    }

    public function update()
    {
      $db = openConnection();
      $stmt = $db->prepare("UPDATE application SET uncom_cert=?,com_cert=?,purpose_statement=? WHERE app_num=?");
      $stmt->bind_param("sssi", $this->uncom_cert,$this->com_cert,$this->purpose_statement,$this->app_num);
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
      $stmt = $db->prepare("DELETE FROM application WHERE app_num=?");
      $stmt->bind_param("i", $this->app_num);
      $success = $stmt->execute();
  
      if (!$success) {
        throw new \Exception($stmt->error);
      }
      $stmt->close();
      $db->close();
    }

    public static function fromResult($result)
    {
      $application = new Application();
      $application->UIN  = $result['UIN'];
      $application->app_num  = $result['app_num'];
      $application->program_num  = $result['program_num'];
      $application->uncom_cert  = $result['uncom_cert'];
      $application->com_cert  = $result['com_cert'];
      $application->purpose_statement  = $result['purpose_statement'];

      return $application;
    }

    public static function all($id)
    {
      $db = openConnection();
      $stmt = $db->prepare("SELECT * FROM application WHERE UIN=?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $result = $stmt->get_result();
      $applications = [];
      while ($application = $result->fetch_assoc()) {
        $applications[] = Application::fromResult($application);
      }
      $stmt->close();
      $db->close();
      return $applications;
    }

    public static function get($id)
    {
      $db = openConnection();
      $stmt = $db->prepare("SELECT * from application WHERE app_num=?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $result = $stmt->get_result();
      $app = $result->fetch_assoc();
      if(!$app){
        return null;
      }
      $app = Application::fromResult($app);
      $stmt->close();
      $db->close();
      return $app;
    }


}