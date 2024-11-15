<?php
// Establish a database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "access_form";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the username is provided in the request
if(isset($_POST['username'])) {
    $username = $_POST['username'];

    // Query to fetch user details based on the username
    $sqluser = "SELECT last_name, middle_name, first_name, department_name, email, position_name, username
            FROM employee_access
            WHERE username = '$username'";
    $resultuser = $conn->query($sqluser);

    if ($resultuser->num_rows > 0) {
        // Fetch user details from the result set
        $row = $resultuser->fetch_assoc();

        // Close database connection
        $conn->close();

        // Return user details as JSON response
        echo json_encode($row);
    } else {
        // If no user found with the provided username
        echo json_encode(array("error" => "User not found"));
    }
} else {
    // If username is not provided in the request
    echo json_encode(array("error" => "Username not provided"));
}
?>
