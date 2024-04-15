<?php
session_start();

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "file_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" and isset($_POST)) {
    $inputUsername = $_POST["username"];
    $inputPassword = $_POST["password"];

    // SQL query to check if user credentials are correct
    $sql = "SELECT * FROM users WHERE username='$inputUsername' AND password='$inputPassword'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // If the credentials are correct, redirect to index.php
        header("Location: index.php");
        exit;
    } else {
        // If the credentials are incorrect, show a pop-up message
        echo "<script>alert('Incorrect username or password. Please try again.');</script>";
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>
<body>
    <h2>Login</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>
