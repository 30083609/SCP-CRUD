<?php
// Enable error reporting for debugging (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include config.php for database connection
require_once __DIR__ . '/../config.php';

// Check if the form data is set
if (!isset($_POST['scp_id']) || !isset($_POST['title']) || !isset($_POST['object_class']) || !isset($_POST['description']) || !isset($_POST['procedures'])) {
    die('Form data not set. Please fill in all required fields.');
}

// Get form data and sanitize inputs
$scp_id = $conn->real_escape_string($_POST['scp_id']);
$title = $conn->real_escape_string($_POST['title']);
$object_class = $conn->real_escape_string($_POST['object_class']);
$description = $conn->real_escape_string($_POST['description']);
$procedures = $conn->real_escape_string($_POST['procedures']);
$image_path = ''; // Initialize as empty in case no image is uploaded

// Set the correct path to the images directory relative to create.php
$upload_dir = '../images/';  // Adjust path based on your directory structure

// Check if the images directory exists, create it if not
if (!is_dir($upload_dir)) {
    if (!mkdir($upload_dir, 0755, true)) {
        die("Failed to create the images directory.");
    }
}

// Check if an image is uploaded and handle file upload
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    // Get the file name and ensure it's unique by prefixing a timestamp
    $file_name = time() . "_" . basename($_FILES['image']['name']);
    $target_file = $upload_dir . $file_name;

    // Validate that the file is an image
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $valid_extensions = array("jpg", "jpeg", "png", "gif", "webp");  // Add 'webp' to the allowed formats

    if (in_array($imageFileType, $valid_extensions)) {
        // Move the uploaded file to the server
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_path = 'Assignment3/images/' . $file_name; // Store the path to the image for the database
        } else {
            die("Error moving the uploaded file.");
        }
    } else {
        die("Invalid file type. Only JPG, JPEG, PNG, GIF, and WEBP files are allowed.");
    }
} else {
    echo "No image was uploaded or an error occurred.";
}

// Insert data into the database, including the image path (if available)
$query = "INSERT INTO scp_subjects (scp_id, title, object_class, description, procedures, images) 
          VALUES ('$scp_id', '$title', '$object_class', '$description', '$procedures', '$image_path')";

if ($conn->query($query) === TRUE) {
    echo "SCP entry created successfully!";
} else {
    echo "Error: " . $conn->error;
}

// Close the database connection
$conn->close();
?>
