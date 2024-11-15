<?php
session_start();
include 'include.php'; // Include your database connection

// Include PHPMailer files
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

// Function to send email notifications
function sendEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = '172.18.155.32'; // Update with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'notifications@kcblbank.co.tz'; // Update with your SMTP username
        $mail->Password = 'Balancesheet@2026'; // Update with your SMTP password
       // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 25;

        // Recipients
        $mail->setFrom('notifications@kcblbank.co.tz', 'Safari Imprest Notifications');
        $mail->addAddress($to);

        // Content 
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $imprest_id = $_POST['imprest_id'];
    $comment = $_POST['comment'];
    $position_name = $_SESSION['Position_name']; // Assume Position_name is stored in session
    $username = $_SESSION['username'];

    // Fetch the signature path from the signature table
    $sql_fetch_signature = "SELECT signature_path FROM signature WHERE username = ?";
    $stmt_fetch_signature = $conn->prepare($sql_fetch_signature);

    if ($stmt_fetch_signature) {
        $stmt_fetch_signature->bind_param("s", $username);
        $stmt_fetch_signature->execute();
        $stmt_fetch_signature->bind_result($signature_path);
        $stmt_fetch_signature->fetch();
        $stmt_fetch_signature->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement to fetch signature path: ' . $conn->error]);
        exit(); // Exit script if statement preparation fails
    }

    // Check if the action has already been taken by the same Position_name
    $sql_check_action = "SELECT COUNT(*) AS action_exists FROM imprest_action_safari WHERE username = ? AND imprest_id = ?";
    $stmt_check_action = $conn->prepare($sql_check_action);
    $stmt_check_action->bind_param("si", $username, $imprest_id);
    $stmt_check_action->execute();
    $result_check_action = $stmt_check_action->get_result();
    $row_check_action = $result_check_action->fetch_assoc();
    $stmt_check_action->close();

    if ($row_check_action['action_exists'] > 0) {
        // Action already taken by the same username, prevent duplicate action
        echo json_encode(['status' => 'action_taken']);
        exit();
    }

    // Insert the new action
    $sql_insert_action = "INSERT INTO imprest_action_safari (imprest_id, username, status, comment, signature_path, position_name) VALUES (?, ?, 'declined', ?, ?, ?)";
    $stmt_insert_action = $conn->prepare($sql_insert_action);
    if ($stmt_insert_action) {
        $stmt_insert_action->bind_param("issss", $imprest_id, $username, $comment, $signature_path, $position_name);
        if ($stmt_insert_action->execute()) {
            // Update the status of the memo in the database
            $sql_update_memo = "UPDATE imprest_safari SET status = 'declined' WHERE imprest_id = ?";
            $stmt_update_memo = $conn->prepare($sql_update_memo);
            if ($stmt_update_memo) {
                $stmt_update_memo->bind_param("i", $imprest_id);
                if ($stmt_update_memo->execute()) {
                    echo json_encode(['status' => 'success']);
                    
                    // Fetch the email of the imprest creator
                    $sql_fetch_creator_email = "SELECT e.email, i.username FROM imprest_safari i
                                                JOIN employee_access e ON i.username = e.username
                                                WHERE i.imprest_id = ?";
                    $stmt_fetch_creator_email = $conn->prepare($sql_fetch_creator_email);
                    if ($stmt_fetch_creator_email) {
                        $stmt_fetch_creator_email->bind_param("i", $imprest_id);
                        $stmt_fetch_creator_email->execute();
                        $stmt_fetch_creator_email->bind_result($creator_email, $creator_username);
                        $stmt_fetch_creator_email->fetch();
                        $stmt_fetch_creator_email->close();
                    }

                    // Send email notification if the creator's email is found
                    if ($creator_email) {
                        $subject = "Safari Imprest Request Declined";
                        $body = "Dear $creator_username,<br><br>Your safari imprest request (ID: $imprest_id) has been declined by $username.";
                        sendEmail($creator_email, $subject, $body);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error updating memo status: ' . $stmt_update_memo->error]);
                }
                $stmt_update_memo->close();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to prepare update statement for memo: ' . $conn->error]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error inserting details into imprest_action_safari table: ' . $stmt_insert_action->error]);
        }
        $stmt_insert_action->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare insert statement for imprest_action_safari: ' . $conn->error]);
    }
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
