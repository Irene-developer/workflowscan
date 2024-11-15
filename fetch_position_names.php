<?php
include 'include.php';

// Fetch department names from the database
$sql = "SELECT Position_name FROM position";
$result = $conn->query($sql);

$positionNames = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $positionNames[] = $row;
    }
}
/*UPDATE employee_access AS ea
JOIN position AS p ON ea.position_name = p.position_name
SET ea.position_id = p.position_id;
*/
// Return department names as JSON
echo json_encode($positionNames);
?>
