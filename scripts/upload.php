<?php
// Include the config.php file for database connection
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $scp_number = $_POST['scp_id'];  // SCP number provided in the form
    $image = $_FILES['image'];

    // Validate SCP number format (e.g., scp-003)
    if (is_numeric($scp_number) && preg_match('/^\d{3}$/', $scp_number)) {
        $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
        $target_dir = "images/";
        $target_file = $target_dir . "scp-" . str_pad($scp_number, 3, '0', STR_PAD_LEFT) . "." . $extension;

        // Move uploaded file to images directory and rename it based on SCP number
        if (move_uploaded_file($image['tmp_name'], $target_file)) {
            // Save the file path to the database
            $image_path = $conn->real_escape_string($target_file);

            $query = "INSERT INTO scp_images (scp_number, image_path) VALUES ('scp-" . str_pad($scp_number, 3, '0', STR_PAD_LEFT) . "', '$image_path')";
            if ($conn->query($query)) {
                echo "File uploaded successfully and saved as SCP-" . $scp_number;
            } else {
                echo "Error saving image path: " . $conn->error;
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
