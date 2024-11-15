<?php
// delete_employee.php
include 'include.php'; // Your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['employeeId'])) {
        $employeeId = $_POST['employeeId'];
        
        // Prepare and execute delete statement
        $stmt = $conn->prepare("DELETE FROM employee_access WHERE id = ?");
        $stmt->bind_param("i", $employeeId);
        
        if ($stmt->execute()) {
            echo "Success";
        } else {
            echo "Error";
        }
        
        $stmt->close();
    }
}
?>
