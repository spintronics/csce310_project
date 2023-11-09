<?php
// Kevin Brown
namespace App;

use DateTime;

require_once __DIR__ . '/../util.php';

class Application
{
  public int $id;
  public DateTime $date;
  public String $purpose_statement;
  public Student $student;

  public function save()
  {
    $db = openConnection();
    $stmt = $db->prepare("INSERT INTO application (date, purpose_statement, student_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $this->date, $this->purpose_statement, $this->student->id);
    $stmt->execute();
    $stmt->close();
    $db->close();
  }

  public static function find($id)
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM application WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $application = $result->fetch_assoc();
    $stmt->close();
    $db->close();
    return Application::fromResult($application);
  }

  public static function all()
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM application");
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

  public static function fromResult($result)
  {
    $application = new Application();
    $application->id = $result['id'];
    $application->date = $result['date'];
    $application->purpose_statement = $result['purpose_statement'];
    $application->student = Student::find($result['student_id']);
    return $application;
  }
}
