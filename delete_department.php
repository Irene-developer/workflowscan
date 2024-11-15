<?php
// Include database connection
include 'include.php';

// Check if the department ID is received via POST request
if(isset($_POST['department_id'])) {
    // Sanitize the received department ID
    $department_id = $_POST['department_id'];

    // Validate the department ID
    if(!empty($department_id) && is_numeric($department_id)) {
        // Execute SQL DELETE query to remove the department
        $sql = "DELETE FROM department WHERE department_id = $department_id";

        if ($conn->query($sql) === TRUE) {
            // Provide success message
            echo "Department deleted successfully";
        } else {
            // Provide error message
            echo "Error deleting department: " . $conn->error;
        }
    } else {
        // Provide error message for invalid department ID
        echo "Invalid department ID";
    }
} else {
    // Provide error message if department ID is not received
    echo "Department ID not provided";
}

// Close database connection
$conn->close();
?>

