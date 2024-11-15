<?php
// Start session
session_start();

// Include database connection
include 'include.php';

// Check if the user is logged in
if (isset($_SESSION['employee_id'])) {
    // Get employee ID from session
    $employee_id = $_SESSION['employee_id'];

    // Capture the current time as the logout time
    $logout_time = date("Y-m-d H:i:s");

    // Prepare the SQL query to update the logout time in the user_logs table
    $query = "UPDATE user_logs 
              SET time_logged_out = ? 
              WHERE employee_id = ? 
              AND time_logged_out IS NULL"; // Ensure we only update the current session's log

    // Prepare the statement
    if ($stmt = $conn->prepare($query)) {
        // Bind parameters to prevent SQL injection ('si' stands for string and integer)
        $stmt->bind_param('si', $logout_time, $employee_id);

        // Execute the statement and check if it was successful
        if ($stmt->execute()) {
            // Successfully updated the logout time
            $response = array('success' => true, 'message' => 'Logged out successfully.');
        } else {
            // Handle error if the query execution failed
            $response = array('success' => false, 'message' => 'Error updating logout time: ' . $stmt->error);
        }

        // Close the statement
        $stmt->close();
    } else {
        // Handle error if the statement preparation failed
        $response = array('success' => false, 'message' => 'Error preparing statement: ' . $conn->error);
    }
} else {
    // Handle case when user is not logged in
    $response = array('success' => false, 'message' => 'User is not logged in.');
}

// Destroy the session to log the user out
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session data

// Send JSON response (if using AJAX)
header('Content-Type: application/json');
echo json_encode($response);

// Redirect to login page after logout (if not using AJAX)
header("Location: index.php");
exit();
?>
