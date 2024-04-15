<?php



// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "file_management";

$conn = new mysqli($servername, $username, $password, $dbname);




// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the filename from the AJAX request
$id = $_POST['id'];
$fileName = $_POST['fileName'];

// Delete the record from the database
$sql = "DELETE FROM files WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);
$stmt->execute();

// Check if the deletion was successful
if ($stmt->affected_rows > 0) {
    // Delete the file
    $filePath = $fileName;
    if (file_exists($filePath)) {
        unlink($filePath); // Delete the file
        echo "Record and file deleted successfully.";
    } else {
        echo "Record deleted but file not found.";
    }
} else {
    echo "Error deleting record.";
}

$stmt->close();
$conn->close();
?>
