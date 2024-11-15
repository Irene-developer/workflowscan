<?php
// Establish a connection to your MariaDB database
$servername = "localhost"; // Change this to your database server name
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$dbname = "system_access"; // Change this to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the submitted form
    $supervisor_action = $_POST["supervisor_action"];
    $supervisor_justification = $_POST["supervisor_justification"];
    $supervised_by = $_POST["supervised_by"]; // Retrieve the supervised_by value from the form

    // Update the database with supervisor action and justification
    $sql = "UPDATE user_input_data SET supervisor_action=?, supervisor_justification=?, supervised_by=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $supervisor_action, $supervisor_justification, $supervised_by);
    $stmt->execute();

    // Check if the update was successful
    if ($stmt->affected_rows > 0) {
        echo "Supervisor action and justification updated successfully.";
    } else {
        echo "Error updating supervisor action and justification: " . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
