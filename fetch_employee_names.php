<?php
// Connect to your database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "access_form";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch employee names
$sql = "SELECT CONCAT(last_name, ' ', middle_name, ' ', first_name) AS names FROM employee_access";
$result = $conn->query($sql);

$names = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $names[] = $row;
    }
}

// Close database connection
$conn->close();

// Return employee names as JSON
echo json_encode($names);
?>
