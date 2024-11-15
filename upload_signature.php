<?php
session_start(); // Start the session

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Get the uploaded file details
    $file_name = $_FILES['signature_file']['name'];
    $file_tmp = $_FILES['signature_file']['tmp_name'];
    $file_type = $_FILES['signature_file']['type'];
    $file_size = $_FILES['signature_file']['size'];
    
    // Check if file is uploaded successfully
    if ($file_tmp) {
        // Check if the file is an image
        $allowed_types = array("image/jpeg", "image/png");
        if (in_array($file_type, $allowed_types)) {
            // Read the file content
            $signature_content = file_get_contents($file_tmp);
            
            // Connect to your database (replace placeholders with your actual database credentials)
          include 'include.php';

            // Get the username from session
            $username = $_SESSION['username'];

            // Update the signature in the database
            $sql = "UPDATE employee_access SET signature = ? WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("bs", $signature_content, $username);
            $stmt->execute();

            // Close statement and connection
            $stmt->close();
            $conn->close();

            echo "Signature uploaded successfully.";
        } else {
            echo "Invalid file format. Please upload a JPEG or PNG image.";
        }
    } else {
        echo "Failed to upload file.";
    }
}
?>
