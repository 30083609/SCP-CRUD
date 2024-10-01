<?php
// config.php - Contains database connection details
$servername = "localhost";
$username = "a30083609_SCP-LoganPoole";
$password = "~tK^2C91ghZL";
$database = "a30083609_SCP";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
