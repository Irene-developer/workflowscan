<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

// Include database connection file
include_once "db_connection.php";

// Fetch new memos or updates from the database
$query = "SELECT * FROM Memos WHERE created_at > NOW() - INTERVAL 1 MINUTE";
$result = mysqli_query($conn, $query);

// Send notifications to clients for each new memo or update
while ($row = mysqli_fetch_assoc($result)) {
    echo "event: memoNotification\n";
    echo "data: " . json_encode($row) . "\n\n";
    ob_flush();
    flush();
}

mysqli_close($conn);
?>
