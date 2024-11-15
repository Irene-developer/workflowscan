<?php
// Database connection parameters
include 'include.php';

// Get the employee ID from the query string
$employeeId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($employeeId > 0) {
    // Prepare and execute the SQL query
    $stmt = $conn->prepare('SELECT id, last_name, middle_name, first_name, department_name, email, password, Position_name, employee_type FROM employee_access WHERE id = ?');
    $stmt->bind_param('i', $employeeId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the details
    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
        echo json_encode($employee);
    } else {
        echo json_encode(['error' => 'Employee not found']);
    }

    // Close the statement
    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid ID']);
}

// Close the connection
$conn->close();
?>
