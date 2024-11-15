<?php
// approve.php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Assuming you have a database connection already established
    $memoId = $_POST["memo_id"];
    // Perform approval logic here using $memoId
    // For example, update the status of the memo in the database
    // and return a success message
    echo "Memo with ID $memoId has been approved.";
} else {
    // Return an error response if accessed via GET request
    http_response_code(405);
    echo "Method Not Allowed";
}
?>
