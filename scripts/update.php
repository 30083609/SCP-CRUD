<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include config.php for database connection
require_once __DIR__ . '/../config.php';

// Check if SCP ID is set
if (isset($_POST['scp_id'])) {
    $scp_id = $conn->real_escape_string($_POST['scp_id']);

    // First, retrieve the current data including the image path
    $stmt = $conn->prepare("SELECT title, object_class, description, procedures, images FROM scp_subjects WHERE scp_id = ?");
    $stmt->bind_param("s", $scp_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $current_data = $result->fetch_assoc();
    $stmt->close();

    if (!$current_data) {
        die("No SCP entry found for SCP ID: " . htmlspecialchars($scp_id));
    }

    // Initialize variables with current values
    $title = $current_data['title'];
    $object_class = $current_data['object_class'];
    $description = $current_data['description'];
    $procedures = $current_data['procedures'];
    $current_image_path = $current_data['images'];

    // Update fields if new values are provided
    if (!empty($_POST['title'])) {
        $title = $conn->real_escape_string($_POST['title']);
    }
    if (!empty($_POST['object_class'])) {
        $object_class = $conn->real_escape_string($_POST['object_class']);
    }
    if (!empty($_POST['description'])) {
        $description = $conn->real_escape_string($_POST['description']);
    }
    if (!empty($_POST['procedures'])) {
        $procedures = $conn->real_escape_string($_POST['procedures']);
    }

    // Handle image update if a new one is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Upload directory
        $upload_dir = '../images/';
        $file_name = time() . "_" . basename($_FILES['image']['name']);
        $target_file = $upload_dir . $file_name;

        // Validate image type (including webp)
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $valid_extensions = array("jpg", "jpeg", "png", "gif", "webp");

        if (in_array($imageFileType, $valid_extensions)) {
            // Move new image and delete the old one
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                // Delete the old image if it exists
                if (!empty($current_image_path) && file_exists(__DIR__ . '/../' . $current_image_path)) {
                    unlink(__DIR__ . '/../' . $current_image_path);
                }
                // Update image path
                $current_image_path = 'Assignment3/images/' . $file_name;
            } else {
                echo "Error uploading the new image.";
            }
        } else {
            die("Invalid file type. Only JPG, JPEG, PNG, GIF, and WEBP files are allowed.");
        }
    }

    // Prepare and bind for update
    $stmt = $conn->prepare("UPDATE scp_subjects SET title = ?, object_class = ?, description = ?, procedures = ?, images = ? WHERE scp_id = ?");
    $stmt->bind_param("ssssss", $title, $object_class, $description, $procedures, $current_image_path, $scp_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "SCP entry updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "SCP ID not set.";
}

// Close the database connection
$conn->close();
?>
