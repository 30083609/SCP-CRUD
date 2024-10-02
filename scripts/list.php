<?php
// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include your database connection
require_once __DIR__ . '/../config.php';

// Initialize an empty array to hold the SCP entries
$scp_entries = [];

// Query to fetch all SCP entries from the database without the image field
$query = "SELECT scp_id, title, object_class, description, procedures FROM scp_subjects";  // Exclude 'images'
$result = $conn->query($query);

// Check if the query was successful
if ($result && $result->num_rows > 0) {
    // Fetch all rows as associative arrays and push to $scp_entries array
    while ($row = $result->fetch_assoc()) {
        $scp_entries[] = [
            'scp_id' => $row['scp_id'],
            'title' => $row['title'],
            'object_class' => $row['object_class'],
            'description' => $row['description'],
            'procedures' => $row['procedures']
        ];
    }
} else {
    // Optional: Handle no results
    http_response_code(404);
    $scp_entries = ['message' => 'No SCP entries found.'];
}

// Set content type to JSON
header('Content-Type: application/json');

// Output JSON
echo json_encode($scp_entries);

// Close the database connection
$conn->close();
?>
