<?php
// fetch_all_employee_details.php
include 'include.php';

$sql = "SELECT * FROM employee_access";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);

$conn->close();
?>
