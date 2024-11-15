<?php
include 'include.php';

// Fetch department names from the database
$sql = "SELECT 
    MAX(department_id) AS department_id,
    department_name,
    MAX(sub_department) AS sub_department,
    MAX(Head_of_subdepartment) AS Head_of_subdepartment,
    MAX(Head_of_department) AS Head_of_department
FROM 
    department
GROUP BY 
    department_name;
";
$result = $conn->query($sql);

$departmentNames = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $departmentNames[] = $row;
    }
}

// Return department names as JSON
echo json_encode($departmentNames);
?>
