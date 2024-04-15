<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);



// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "file_management";

$conn = new mysqli($servername, $username, $password, $dbname);



// Function to retrieve files from the database
function getFiles($conn, $searchQuery = "") {
    $sql = "SELECT * FROM files";
    if (!empty($searchQuery)) {
        $sql .= " WHERE name LIKE '%$searchQuery%' OR language LIKE '%$searchQuery%'";
    }
    //var_dump($sql);
    $result = $conn->query($sql);
    $files = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $files[] = $row;
        }
    }

    return $files;
}

// Function to add a new file to the database and upload it to the 'contenu' folder
 function addFile($conn, $name, $language, $file) {
        // Check if uploaded file is XML
      //  if ($file['type'] === 'text/xml') {
            $uploadDirectory = 'contenu/';
            $targetFileName = $uploadDirectory . $name . '_' . $language . '_' . time() . '.xml';
            // Move the uploaded file to the destination directory
            if (move_uploaded_file($file["tmp_name"], $targetFileName)) {
                // Insert file info into database
                $sql = "INSERT INTO files (name, language,filename) VALUES ('$name', '$language','$targetFileName')";
                if ($conn->query($sql) === TRUE) {
                    echo "New record created successfully";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "Error moving uploaded file.";
            }
       /* } else {
            echo "Only XML files are allowed.";
        }*/
    }



if (isset($_POST['fileName']) &&  isset($_POST['fileLanguage']) && isset($_FILES['fileUpload']) && $_FILES['fileUpload']['error'] === UPLOAD_ERR_OK )
{   
    addFile($conn, $_POST['fileName'], $_POST['fileLanguage'], $_FILES['fileUpload']);
    // Redirect to the same page to prevent resubmission
  
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
  

}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Management</title>
    <link rel="stylesheet" href="styles.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
</head>
<body>

<div class="container">
    <h2>File Management</h2>
    <div class="search-container">
        <form action="index.php" method="GET">
            <input type="text" name="search" id="searchInput" placeholder="Search...">
            <button type="submit">Search</button>
        </form>
    </div>
    <button onclick="openModal()">Add File</button>
    
<!-- Modal for adding files -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3>Add New File</h3>
        <form action="index.php" method="POST" enctype="multipart/form-data">
            <label for="fileName">Name:</label>
            <input type="text" id="fileName" name="fileName">
            <label for="fileLanguage">Language:</label>
            <input type="text" id="fileLanguage" name="fileLanguage">
            <label for="fileUpload">File:</label>
            <input type="file" id="fileUpload" name="fileUpload">
            <button type="submit">Add</button>
        </form>
    </div>
</div>

  
<table id="fileTable">
        <tr>
            <th>Name</th>
            <th>Language</th>
            <th>Actions</th>
        </tr>
        <!-- PHP code to populate the table will go here -->




<?php
// Get search query if provided
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';



// Display files in the table
$files = getFiles($conn,$searchQuery);
foreach ($files as $file) {
   
    echo "<tr>";
    echo "<td>" . $file['name'] . "</td>";
    echo "<td>" . $file['language'] . "</td>";
    echo "<td>";
    echo "<button onclick='checkContent(" . $file['id'] . ")'>Check Content</button>";
    echo "<button class='deleteButton' data-id='" . $file['id'] . "' data-FileName='" . $file['filename'] ."'>Delete</button>";


  
    echo "<button onclick='updateFile(" . $file['id'] . ")'>Update</button>";
    echo "</td>";
    echo "</tr>";
}
?>
    </table>
</div>

    <?php


// Close the database connection
$conn->close();
  unset($_POST);
?>

<script type="text/javascript">


function openModal() {
    document.getElementById('myModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('myModal').style.display = 'none';
}


</script>

    </body>
    </html>




