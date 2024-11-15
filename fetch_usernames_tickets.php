<?php
header('Content-Type: application/json');
include 'include.php';


$sql = "SELECT id, username FROM employee_access WHERE department_name = 'ICT Department'";
$result = $conn->query($sql);

$usernames = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $usernames[] = $row;
    }
}

echo json_encode($usernames);

$conn->close();
?>
