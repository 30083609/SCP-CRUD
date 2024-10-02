<?php
// config.php - Contains database connection details
$servername = "*DATABASE (localhost)*";
$username = "DATABASE USERNAME";
$password = "*PASSWORD*";
$database = "*DATABASE*";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
