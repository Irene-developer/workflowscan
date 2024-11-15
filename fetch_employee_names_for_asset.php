<?php
include 'include.php';

// Fetch employee names from the database
$sql = "SELECT id, username, first_name, last_name FROM employee_access";
$result = $conn->query($sql);

$employees = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }
}

// Return the data as JSON
echo json_encode($employees);

// Close the database connection
$conn->close();
?>
