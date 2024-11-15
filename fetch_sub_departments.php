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

// Query to fetch department names
$sql = "SELECT sub_department FROM department";
$result = $conn->query($sql);

$subdepartments = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $subdepartments[] = $row;
    }
}

// Close database connection
$conn->close();

// Return department names as JSON
echo json_encode($subdepartments);
?>
