<?php
session_start();
include 'include.php'; // Ensure to include your database connection file

// Get the department from the POST request
$data = json_decode(file_get_contents("php://input"), true);
$department_name = $data['department_name'];

// If the department name is 'session', use the session's department name
if ($department_name === 'session') {
    $department_name = $_SESSION['department_name'];
}

// Prepare and execute the query
$query = "SELECT name, username FROM employee_access WHERE department_name = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $department_name);
$stmt->execute();
$result = $stmt->get_result();

// Fetch all the records
$employees = [];
while ($row = $result->fetch_assoc()) {
    $employees[] = $row;
}

// Return the data as JSON
echo json_encode($employees);

$stmt->close();
$conn->close();
?>
