<?php
// process_login.php

// Start session
session_start();

include 'include.php'; // Include your database connection file

// Initialize response array
$response = array('success' => false, 'message' => '', 'username' => '');

try {
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if username is set and not empty
        if (isset($_POST["username"]) && !empty($_POST["username"])) {
            // Sanitize user input to prevent SQL Injection
            $username = $conn->real_escape_string($_POST["username"]);
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $login_status = 'failed'; // Default

            // Retrieve user data from the database based on the provided username
            $sql = "SELECT * FROM employee_access WHERE username = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                throw new Exception("Database prepare error: " . $conn->error);
            }
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // User found, set success flag to true
                $row = $result->fetch_assoc();
                $response['success'] = true;
                $response['username'] = $row['username'];
                $employee_id = $row['id'];
                $login_status = 'successful';

                // Store user's name, Position_name, and department name in session
                $_SESSION['username'] = $row['username'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['department_name'] = $row['department_name'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['Position_name'] = $row['Position_name'];
                $_SESSION['employee_type'] = $row['employee_type'];
                $_SESSION['employee_id'] = $employee_id; // Set session for logged-in user

                // Log login attempt
                $log_query = "INSERT INTO user_logs (employee_id, time_logged_in, ip_address, login_status) VALUES (?, NOW(), ?, ?)";
                $log_stmt = $conn->prepare($log_query);
                if ($log_stmt === false) {
                    throw new Exception("Log query prepare error: " . $conn->error);
                }
                $log_stmt->bind_param('iss', $employee_id, $ip_address, $login_status);
                $log_stmt->execute();
            } else {
                $response['message'] = "User not found.";

                // Log failed login attempt
                $log_query = "INSERT INTO user_logs (employee_id, time_logged_in, ip_address, login_status) VALUES (NULL, NOW(), ?, ?)";
                $log_stmt = $conn->prepare($log_query);
                if ($log_stmt === false) {
                    throw new Exception("Log query prepare error: " . $conn->error);
                }
                $log_stmt->bind_param('ss', $ip_address, $login_status);
                $log_stmt->execute();
            }
        } else {
            // Handle if username is empty
            $response['message'] = "Please enter a username.";
        }
    } else {
        // Handle if form is not submitted
        $response['message'] = "Form not submitted.";
    }
} catch (Exception $e) {
    // Handle any errors that occur
    $response['message'] = "An error occurred: " . $e->getMessage();
}

// Close database connection
$conn->close();

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
