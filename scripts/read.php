<?php
// Enable error reporting for debugging (disable it in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the config.php file for database connection
require_once '../config.php'; // Adjust the path to point to the config.php in the root directory

// Check if the POST request contains the scp_id
if (!isset($_POST['scp_id']) || empty($_POST['scp_id'])) {
    die('Error: SCP ID is missing');
}

$scp_number = $_POST['scp_id']; // Retrieve the SCP number from the POST request

// Sanitize the input to prevent SQL injection
$scp_number = $conn->real_escape_string($scp_number);

// Construct the query
$query = "SELECT * FROM scp_subjects WHERE scp_id = '$scp_number'";

// Execute the query
$result = $conn->query($query);

// Check if the query succeeded
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $title = $row['title'];
    $object_class = $row['object_class'];
    $description = $row['description'];
    $procedures = $row['procedures'];
    $image_path = $row['images'];

    // Display the SCP entry and its fields
    echo "<h2>" . htmlspecialchars($title) . " (" . htmlspecialchars($scp_number) . ")</h2>";
    echo "<p><strong>Object Class:</strong> " . htmlspecialchars($object_class) . "</p>";
    echo "<p><strong>Description:</strong> " . htmlspecialchars($description) . "</p>";
    echo "<p><strong>Special Containment Procedures:</strong> " . htmlspecialchars($procedures) . "</p>";

    // Check if an image is available
    if (!empty($image_path)) {
        echo "<img src='../$image_path' alt='" . htmlspecialchars($scp_number) . " Image' style='max-width:100%; height:auto;' />";
    } else {
        echo "<p>No image available for " . htmlspecialchars($scp_number) . "</p>";
    }
} else {
    echo "<p>No SCP entry found for " . htmlspecialchars($scp_number) . "</p>";
}

// Close the database connection
$conn->close();
?>
