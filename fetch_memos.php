<?php
include 'include.php';

$sql = "SELECT id, subject FROM memos where status = 'approved'";
$result = $conn->query($sql);

$memos = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $memos[] = $row;
    }
} else {
    echo "0 results";
}
$conn->close();

echo json_encode($memos);
?>
