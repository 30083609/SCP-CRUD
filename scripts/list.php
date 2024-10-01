<?php
// Enable error reporting for debugging (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the config.php file for database connection
require_once __DIR__ . '/../config.php';

// Query to fetch all SCP entries and their associated images
$query = "SELECT * FROM scp_subjects";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        $scp_number = isset($row['scp_id']) ? htmlspecialchars($row['scp_id']) : 'Unknown ID';
        $title = isset($row['title']) ? htmlspecialchars($row['title']) : 'Untitled';
        $object_class = isset($row['object_class']) ? htmlspecialchars($row['object_class']) : 'Unknown Class';
        $description = isset($row['description']) ? htmlspecialchars($row['description']) : 'No description available';
        $procedures = isset($row['procedures']) ? htmlspecialchars($row['procedures']) : 'No procedures available';
        $image_path = isset($row['images']) ? htmlspecialchars($row['images']) : '';

        // Display each SCP entry with its details
        echo "<li>";
        echo "<h3>$title ($scp_number)</h3>";
        echo "<p><strong>Object Class:</strong> $object_class</p>";
        echo "<p><strong>Description:</strong> $description</p>";
        echo "<p><strong>Special Containment Procedures:</strong> $procedures</p>";

        // Check if an image exists
        if (!empty($image_path)) {
            echo "<img src='/$image_path' alt='$scp_number Image' style='max-width:150px; height:auto;' />";
        } else {
            echo "<p>No image available for $scp_number</p>";
        }

        echo "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No SCP entries found.</p>";
}

// Close the database connection
$conn->close();
?>
