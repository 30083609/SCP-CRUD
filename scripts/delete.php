<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include config.php for database connection
require_once __DIR__ . '/../config.php';

// Check if SCP ID is set
if (isset($_POST['scp_id'])) {
    $scp_id = $conn->real_escape_string($_POST['scp_id']);

    // First, retrieve the image path for the SCP entry
    $stmt = $conn->prepare("SELECT images FROM scp_subjects WHERE scp_id = ?");
    $stmt->bind_param("s", $scp_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        // Get the image path
        $image_path = $row['images'];

        // Delete the image file if it exists
        if (!empty($image_path) && file_exists(__DIR__ . '/../' . $image_path)) {
            unlink(__DIR__ . '/../' . $image_path);  // Delete the image file
        }

        // Now delete the SCP entry from the database
        $stmt = $conn->prepare("DELETE FROM scp_subjects WHERE scp_id = ?");
        $stmt->bind_param("s", $scp_id);

        // Execute the statement
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "SCP entry and associated image deleted successfully.";
            } else {
                echo "No SCP entry found with ID: " . htmlspecialchars($scp_id);
            }
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "No SCP entry found for SCP ID: " . htmlspecialchars($scp_id);
    }

    $stmt->close();
} else {
    echo "SCP ID not set.";
}

// Close the database connection
$conn->close();
?>
