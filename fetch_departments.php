<?php
// Connect to your database
include 'include.php';

// Query to fetch department names
$sql = "SELECT department_name FROM department";
$result = $conn->query($sql);

$departments = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
}

// Close database connection
$conn->close();

// Return department names as JSON
echo json_encode($departments);
?>
