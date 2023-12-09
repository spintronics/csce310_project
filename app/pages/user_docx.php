<?php
// Saddy Khakimova
namespace App;

use mysqli;

include __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/application.php';


use App\User;
use App\Application;


$user = null;
$student = null;

$user = User::fromSession();

$db = new mysqli("db", "root", "admin", "mydb", "3306");

error_reporting(1);
ini_set('error_reporting', 1);

$user_UIN = $user->UIN;
$user_applicaitons = Application::getApplication( $user_UIN );
$app_num = $user_applicaitons["app_num"];


if( isset( $_POST["add_doc"] ) ) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
        $targetDirectory = "files/";
        if (!file_exists($targetDirectory)) {
            mkdir($targetDirectory, 0777, true);
        }
    
        // Handle file upload
        $targetDirectory = "files/";
        $originalFileName = basename($_FILES["fileToUpload"]["name"]);
        $targetFile = $targetDirectory . $originalFileName;
    
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
        // Check if the file already exists
        if (file_exists($targetFile)) {
            echo "Sorry, file already exists.<br>";
            $uploadOk = 0;
        }
    
        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
    
        // Allow certain file formats
        $allowedFileTypes = ["jpg", "jpeg", "png", "gif", "pdf"];
        if (!in_array($imageFileType, $allowedFileTypes)) {
            echo "Sorry, only JPG, JPEG, PNG, PDF& GIF files are allowed.<br>";
            $uploadOk = 0;
        }
    
        // Determine doc_type from file extension
        $docType = strtoupper($imageFileType);
    
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.<br>";
        } else {
            
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
                // Insert data into the database
                $appNum = $user_UIN; // Replace with the actual application number
                $createdAt = date('Y-m-d H:i:s');
    
                $insertQuery = "INSERT INTO documentation (link, doc_type, app_num) VALUES ('$originalFileName', '$docType', '$app_num')";
    
                if ($db->query($insertQuery) === TRUE) {
                    echo "The file " . htmlspecialchars($originalFileName) . " has been uploaded and saved to the database.<br>";
                } else {
                    echo "Error: " . $insertQuery . "<br>" . $db->error;
                }
            } else {
                echo "Sorry, there was an error uploading your file.<br>";
            }
        }
    }
}


// Delete document if the delete button is clicked
if (isset($_POST['delete'])) {
    $deleteId = $_POST['delete'];
    $selectFileQuery = "SELECT link FROM documentation WHERE doc_num = '$deleteId'";
    $result = $db->query($selectFileQuery);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $fileToDelete = $targetDirectory . $row['link'];

        // Delete record from the database
        $deleteQuery = "DELETE FROM documentation WHERE doc_num = '$deleteId'";
        if ($db->query($deleteQuery) === TRUE) {
            echo "The document has been deleted from the database.<br>";

            // Delete file from the server
            if (file_exists($fileToDelete)) {
                unlink($fileToDelete);
                echo " The file has been deleted from the server.<br>";
            } else {
                echo " The file does not exist on the server.<br>";
            }
        } else {
            echo "Error deleting record: <br>" . $db->error;
        }
    } else {
        echo "Document not found.<br>";
    }
}

// Edit document if the edit button is clicked
if (isset($_POST['edit'])) {
    $editId = $_POST['edit'];
    $newLink = $_POST['newLink'];
    
    // Handle file upload for edit 
    if (!empty($_FILES["fileToUpload"]["name"])) {
        $originalFileName = basename($_FILES["fileToUpload"]["name"]);
        $targetFile = $targetDirectory . $originalFileName;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        
        
        $docType = strtoupper($imageFileType); 
        
        
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
                
                $updateQuery = "UPDATE documentation SET link = '$originalFileName', doc_type = '$docType' WHERE doc_num = '$editId'";
               
                if ($db->query($updateQuery) === TRUE) {
                    echo "The document has been updated with a new file.<br>";
                } else {
                    echo "Error updating record: " . $db->error;
                }

                // Delete the old file from the server
                $selectFileQuery = "SELECT link FROM documentation WHERE doc_num = '$editId'";
                $result = $db->query($selectFileQuery);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $fileToDelete = $targetDirectory . $row['link'];

                    // Delete file from the server
                    if (file_exists($fileToDelete)) {
                        unlink($fileToDelete);
                        echo " The old file has been deleted from the server.<br>";
                    } else {
                        echo " The old file does not exist on the server.<br>";
                    }
                } else {
                    echo "Document not found.";
                }
            } else {
                echo "Sorry, there was an error uploading your file.<br>";
            }
        }
    } else {
        // Update record in the database without changing the file
        $updateQuery = "UPDATE documentation SET link = '$newLink' WHERE id = '$editId'";
        if ($db->query($updateQuery) === TRUE) {
            echo "The document has been updated without changing the file.<br>";
        } else {
            echo "Error updating record: " . $db->error;
        }
    }
}



$appNum = $user_UIN;

$selectQuery = "SELECT * FROM documentation WHERE app_num = '$app_num'";
$result = $db->query($selectQuery);

?>

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
    <input type="submit" value="Upload File" name="add_doc">
</form>

<?php if ($result->num_rows > 0): ?>
    <h2>List of Documents for Application Number <?php echo $app_num; ?>:</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Link</th>
                <th>Document Type</th>
                <td>Link</td>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['doc_num']; ?></td>
                    <td><?php echo $row['link']; ?></td>
                    <td><?php echo $row['doc_type']; ?></td>
                    <td> <a href=" <?php echo "files/" . $row["link"] ?> "> <?php echo "pages/files/" . $row["link"] ?> </a> </td>
                    <td>
                        <form action="" method="post">
                            <input type="hidden" name="delete" value="<?php echo $row['doc_num']; ?>">
                            <input type="submit" value="Delete">
                        </form>
                      <!-- Edit document form -->
                    <form action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="edit" value="<?php echo $row['doc_num']; ?>">
                        <label for="fileToUpload">Select New File:</label>
                        <input type="file" name="fileToUpload" id="fileToUpload" required>
                        <input type="submit" value="Update" names="edit_doc">
                    </form>

                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No documents found for Application Number <?php echo $appNum; ?>.</p>
<?php endif; ?>