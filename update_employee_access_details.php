<?php
session_start();
// Database connection parameters
include 'include.php';

// Get form data
$employeeId = isset($_POST['employeeId']) ? intval($_POST['employeeId']) : 0;
$lastName = isset($_POST['last_name']) ? $conn->real_escape_string($_POST['last_name']) : '';
$middleName = isset($_POST['middle_name']) ? $conn->real_escape_string($_POST['middle_name']) : '';
$firstName = isset($_POST['first_name']) ? $conn->real_escape_string($_POST['first_name']) : '';
$departmentName = isset($_POST['department_name']) ? $conn->real_escape_string($_POST['department_name']) : '';
$email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';
$password = isset($_POST['password']) ? $conn->real_escape_string($_POST['password']) : '';
$positionName = isset($_POST['Position_name']) ? $conn->real_escape_string($_POST['Position_name']) : '';
$employeeType = isset($_POST['employee_type']) ? $conn->real_escape_string($_POST['employee_type']) : '';

// Check if ID is valid
if ($employeeId > 0) {
    // Prepare and execute the SQL update query
    $stmt = $conn->prepare(
        'UPDATE employee_access SET last_name = ?, middle_name = ?, first_name = ?, department_name = ?, email = ?, password = ?, Position_name = ?, employee_type = ? WHERE id = ?'
    );
    $stmt->bind_param(
        'ssssssssi',
        $lastName,
        $middleName,
        $firstName,
        $departmentName,
        $email,
        $password,
        $positionName,
        $employeeType,
        $employeeId
    );

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Details updated successfully.']);
    } else {
        echo json_encode(['error' => 'Error updating details.']);
    }

    // Close the statement
    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid employee ID.']);
}

// Close the connection
$conn->close();
?>
