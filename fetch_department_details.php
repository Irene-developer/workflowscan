<?php
// Include database connection
include 'include.php';

// Check if department ID is set and not empty
if(isset($_POST['department_id']) && !empty($_POST['department_id'])) {
    // Sanitize the department ID to prevent SQL injection
    $department_id = mysqli_real_escape_string($conn, $_POST['department_id']);

    // Fetch department details from the database
    $sql = "SELECT * FROM department WHERE department_id = $department_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch the department details as an associative array
        $department_details = $result->fetch_assoc();

        // Convert department details to JSON format
        $json_response = json_encode($department_details);

        // Output the JSON response
        echo $json_response;
    } else {
        // Department not found
        echo json_encode(array('error' => 'Department not found'));
    }
} else {
    // Department ID not provided
    echo json_encode(array('error' => 'Department ID not provided'));
}

// Close database connection
$conn->close();
?>
