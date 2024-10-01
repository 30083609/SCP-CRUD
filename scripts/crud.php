<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "a30083609_SCP-LoganPoole";
$password = "~tK^2C91ghZL";
$dbname = "a30083609_SCP";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission to add a new SCP entry
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $scp_id = $_POST['scp_id'];
    $title = $_POST['title'];
    $object_class = $_POST['object_class'];
    $description = $_POST['description'];
    $procedures = $_POST['procedures'];

    // SQL Insert Query
    $sql = "INSERT INTO scp_subjects (scp_id, title, object_class, description, procedures)
            VALUES ('$scp_id', '$title', '$object_class', '$description', '$procedures')";

    if ($conn->query($sql) === TRUE) {
        echo "New SCP entry created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
