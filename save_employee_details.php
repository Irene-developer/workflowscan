<?php
// Establish a database connection
include 'include.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $last_name = $_POST['last_name'];
    $middle_name = $_POST['middle_name'];
    $first_name = $_POST['first_name'];
    $department_name = $_POST['departmentName'];
    $email = $_POST['email'];
    $password = $_POST['Password'];
    $position_name = $_POST['positionName'];

    // SQL query to insert data into the database
    $sql = "INSERT INTO employee_access (last_name, middle_name, first_name, department_name, email, password, position_name)
            VALUES ('$last_name', '$middle_name', '$first_name', '$department_name', '$email', '$password', '$position_name')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();
?>
