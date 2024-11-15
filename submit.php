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

// Process form submission if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST["name"];
    $request_type = $_POST["request_type"];
    $designation = $_POST["designation"];
    $branch_hq = $_POST["branch_hq"];
    $system_name = $_POST["system_name"];
    $as_role = $_POST["as_role"];
    $justification = $_POST["justification"];
    $date = $_POST["date"];
    
    // Insert data into the database
    $sql = "INSERT INTO user_input_data (name, request_type, designation, branch_hq, system_name, as_role, justification, date)
    VALUES ('$name', '$request_type', '$designation', '$branch_hq', '$system_name', '$as_role', '$justification', '$date')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
