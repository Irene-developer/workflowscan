<?php
/* Check if content is received
if (isset($_POST['content'])) {
    // Process notification content
    $notification_content = $_POST['content'];
    
    // Append notification to the notification container
    $notification_html = '<div class="notification">' . $notification_content . '</div>';
    
    // Example: Log notification content
    file_put_contents('notification_log.txt', $notification_content . PHP_EOL, FILE_APPEND);
    
    // Send response
    echo 'Notification received and processed.';
} else {
    // Invalid request
    http_response_code(400);
    echo 'Bad Request';
}*/
?>
