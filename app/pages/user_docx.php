<?php
use mysqli;

include __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../models/user.php';


use App\User;

namespace App;

$user = null;
$student = null;

$user = User::fromSession();

$db = new mysqli("db", "root", "admin", "mydb", "3306");

error_reporting(0);
ini_set('error_reporting', 0);

$user_UIN = $user->UIN;

// Create the table if it doesn't exist
$createTableQuery = "CREATE TABLE IF NOT EXISTS user_docs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id TEXT,
    file_name VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$db->query($createTableQuery);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $targetDirectory = "files/";
    $originalFileName = basename($_FILES["fileToUpload"]["name"]);
    $timestamp = time(); // Get current timestamp
    $newFileName = $timestamp . "-doc." . pathinfo($originalFileName, PATHINFO_EXTENSION);
    $targetFile = $targetDirectory . $newFileName;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if (file_exists($targetFile)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    $allowedFileTypes = ["jpg", "jpeg", "png", "gif", "pdf"];
    if (!in_array($imageFileType, $allowedFileTypes)) {
        echo "Sorry, only JPG, JPEG, PNG, PDF & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // If everything is ok, try to upload file
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
            // Insert data into the database
            $studentId = $user_UIN; // Replace with the actual student ID
            $createdAt = date('Y-m-d H:i:s');

            $insertQuery = "INSERT INTO user_docs (student_id, file_name, created_at) VALUES ('$studentId', '$newFileName', '$createdAt')";

            if ($db->query($insertQuery) === TRUE) {
                echo "The file " . htmlspecialchars($originalFileName) . " has been uploaded and saved to the database.";
            } else {
                echo "Error: " . $insertQuery . "<br>" . $db->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

// Delete document if the delete button is clicked
if (isset($_POST['delete'])) {
    $deleteId = $_POST['delete'];
    $selectFileQuery = "SELECT file_name FROM user_docs WHERE id = '$deleteId'";
    $result = $db->query($selectFileQuery);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $fileToDelete = $targetDirectory . $row['file_name'];

        // Delete record from the database
        $deleteQuery = "DELETE FROM user_docs WHERE id = '$deleteId'";
        if ($db->query($deleteQuery) === TRUE) {
            echo "The document has been deleted from the database.";

            // Delete file from the server
            if (file_exists($fileToDelete)) {
                unlink($fileToDelete);
                echo " The file has been deleted from the server.";
            } else {
                echo " The file does not exist on the server.";
            }
        } else {
            echo "Error deleting record: " . $db->error;
        }
    } else {
        echo "Document not found.";
    }
}

// Edit document if the edit button is clicked
if (isset($_POST['edit'])) {
    $editId = $_POST['edit'];
    $newFileName = $_POST['newFileName'];

    // Update record in the database
    $updateQuery = "UPDATE user_docs SET file_name = '$newFileName' WHERE id = '$editId'";
    if ($db->query($updateQuery) === TRUE) {
        echo "The document has been updated.";
    } else {
        echo "Error updating record: " . $db->error;
    }
}

// Display list of uploaded documents
$studentId = $user_UIN;

$selectQuery = "SELECT * FROM user_docs WHERE student_id = '$studentId'";
$result = $db->query($selectQuery);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uploaded Documents</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        form {
            display: inline-block;
        }
    </style>
</head>
<body>

<form action="" method="post" enctype="multipart/form-data">
    <label for="fileToUpload">Select File</label>
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload File" name="submit">
</form>

<!-- Display list of uploaded documents in a table -->
<?php if ($result->num_rows > 0): ?>
    <h2>List of Documents for Student ID <?php echo $studentId; ?>:</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>File Name</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['file_name']; ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="delete" value="<?php echo $row['id']; ?>">
                            <input type="submit" value="Delete">
                        </form>
                        <form action="" method="post">
                            <input type="hidden" name="edit" value="<?php echo $row['id']; ?>">
                            <label for="newFileName">New File Name:</label>
                            <input type="text" name="newFileName" required>
                            <input type="submit" value="Edit">
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No documents found for Student ID <?php echo $studentId; ?>.</p>
<?php endif; ?>

</body>
</html>