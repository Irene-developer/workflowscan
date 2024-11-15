<?php
// Database connection parameters
$host = 'localhost'; // Replace with your host
$username = 'root'; // Replace with your username
$password = ''; // Replace with your password
$database = 'system_access'; // Replace with your database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch data
$sql = "SELECT * FROM user_input_data";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["name"]. "</td>";
        echo "<td>" . $row["request_type"]. "</td>";
        echo "<td>" . $row["designation"]. "</td>";
        echo "<td>" . $row["branch_hq"]. "</td>";
        echo "<td>" . $row["system_name"]. "</td>";
        echo "<td>" . $row["as_role"]. "</td>";
        echo "<td>" . $row["justification"]. "</td>";
        //echo "<td>" . $row["accept_conditions"]. "</td>";
        echo "<td>" . $row["date"]."</td>";
        echo "<td>" . $row["supervisor_action"]."</td>";
        echo "<td>" . $row["supervisor_justification"]. "</td>";
        echo "<td>" . $row["supervised_by"]. "</td>";
        echo "</tr>";
    }
} else {
    echo "0 results";
}

// Close connection
$conn->close();
?>
