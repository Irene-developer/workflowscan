<?php
session_start();
include 'include.php'; // Database connection

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // Capture current time as logout time
    $logout_time = date("Y-m-d H:i:s");
    
    // Update the user_logs table where the employee is logged in and time_logged_out is NULL
    $update_logout = "UPDATE user_logs 
                      SET time_logged_out = '$logout_time' 
                      WHERE employee_id = $user_id 
                      AND time_logged_out IS NULL";
    
    if ($conn->query($update_logout) === TRUE) {
        // Logout time updated successfully
    } else {
        // Handle error if necessary
        echo "Error updating logout time: " . $conn->error;
    }

    // Destroy the session and redirect to the login page
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

