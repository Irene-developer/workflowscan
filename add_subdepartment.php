<?php
include 'include.php';

// Retrieve data from POST request
$departmentName = $_POST['departmentName'];
$subdepartmentName = $_POST['subdepartmentName'];
$headOfSubDepartment = $_POST['headOfSubDepartment'];

// Update sub-department details in the department table
$sql = "UPDATE department SET sub_department = '$subdepartmentName', Head_of_subdepartment = '$headOfSubDepartment' WHERE department_name = '$departmentName'";

if ($conn->query($sql) === TRUE) {
    echo "Sub-department details updated successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
