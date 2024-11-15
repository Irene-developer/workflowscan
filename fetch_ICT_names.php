<?php
// fetch_names.php
header('Content-Type: application/json');

include 'include.php'; // Include the database connection

// Query to fetch names and ids where department_name is 'ICT Department'
$sql = "SELECT id, name FROM employee_access WHERE department_name = 'INFORMATION AND COMMUNICATION TECHNOLOGY'";
$result = $conn->query($sql);

$names = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $names[] = $row;  // Each row will now contain both 'id' and 'name'
    }
}

echo json_encode($names);  // Return the array as JSON

$conn->close();
?>
