<?php
// Database connection
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

// Retrieve username and new password from POST request
$username = $_POST['username'];
$password = $_POST['password'];

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Update the password in the database
$sql = "UPDATE employee_access SET Password = '$hashedPassword' WHERE username = '$username'";

if ($conn->query($sql) === TRUE) {
    echo "Password reset successfully";
} else {
    echo "Error updating password: " . $conn->error;
}

// Close database connection
$conn->close();
?>
