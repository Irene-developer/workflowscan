<?php
include 'include.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $sql = "INSERT INTO tickets (user_name, user_email, subject, message) VALUES ('$user_name', '$user_email', '$subject', '$message')";

    if ($conn->query($sql) === TRUE) {
        $success_message = "New ticket created successfully";
    } else {
        $error_message = "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Support Ticket System</title>
    <link rel="stylesheet" href="stylestickets.css">
</head>
<body>
    
    <?php
    if (!empty($success_message)) {
        echo "<p class='success'>$success_message</p>";
    } elseif (!empty($error_message)) {
        echo "<p class='error'>$error_message</p>";
    }
    ?>
    
    <form action="" method="POST">
        <label for="user_name">Name:</label>
        <input type="text" id="user_name" name="user_name" required>
        
        <label for="user_email">Email:</label>
        <input type="email" id="user_email" name="user_email" required>
        
        <label for="subject">Subject:</label>
        <input type="text" id="subject" name="subject" required>
        
        <label for="message">Message:</label>
        <textarea id="message" name="message" required></textarea>
        
        <button type="submitt">Submit</button>
    </form>
</body>
</html>
