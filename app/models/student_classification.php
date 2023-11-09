<?php
// Kevin Brown
namespace App;

class StudentClassification
{
  public int $id;
  public string $name;

  public function save()
  {
    $db = openConnection();
    $stmt = $db->prepare("INSERT INTO student_classification (name) VALUES (?)");
    $stmt->bind_param("s", $this->name);
    $stmt->execute();
    $stmt->close();
    $db->close();
  }

  public function find(int $id)
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM `student_classification` WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student_classification = $result->fetch_assoc();
    $stmt->close();
    $db->close();
    return StudentClassification::fromResult($student_classification);
  }

  public function all()
  {
    $db = openConnection();
    $stmt = $db->prepare("SELECT * FROM `student_classification`");
    $stmt->execute();
    $result = $stmt->get_result();
    $student_classifications = [];
    while ($student_classification = $result->fetch_assoc()) {
      $student_classifications[] = StudentClassification::fromResult($student_classification);
    }
    $stmt->close();
    $db->close();
    return $student_classifications;
  }

  public static function fromResult($result)
  {
    $student_classification = new StudentClassification();
    $student_classification->id = $result['id'];
    $student_classification->name = $result['name'];
    return $student_classification;
  }
}
