<?php
// Include the config.php file for database connection
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $scp_number = $_POST['scp_id'];  // SCP number from the form
    $image = $_FILES['image'];

    // Validate SCP number format
    if (is_numeric($scp_number) && preg_match('/^\d{3}$/', $scp_number)) {
        $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
        $target_dir = "images/";
        $target_file = $target_dir . "scp-" . str_pad($scp_number, 3, '0', STR_PAD_LEFT) . "." . $extension;

        // Move the uploaded file to the target directory and rename it
        if (move_uploaded_file($image['tmp_name'], $target_file)) {
            // Update the file path in the database
            $image_path = $conn->real_escape_string($target_file);

            $query = "UPDATE scp_images SET image_path='$image_path' WHERE scp_number='scp-" . str_pad($scp_number, 3, '0', STR_PAD_LEFT) . "'";
            if ($conn->query($query)) {
                echo "Image updated successfully for SCP-" . $scp_number;
            } else {
                echo "Error updating image: " . $conn->error;
            }
        } else {
            echo "Failed to upload file.";
        }
    } else {
        echo "Invalid SCP number.";
    }
}

// Close the database connection
$conn->close();
?>
