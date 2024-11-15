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
        //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
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
    if ($stmt_check_action) {
        $stmt_check_action->bind_param("si", $username, $imprest_id);
        $stmt_check_action->execute();
        $result_check_action = $stmt_check_action->get_result();
        $row_check_action = $result_check_action->fetch_assoc();
        $stmt_check_action->close();

        if ($row_check_action['action_exists'] > 0) {
            // Action already taken by the same Position_name, prevent duplicate action
            echo json_encode(['status' => 'action_taken']);
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare check action statement: ' . $conn->error]);
        exit();
    }

    // Insert the new action
    $sql_insert_action = "INSERT INTO imprest_action_safari (imprest_id, username, status, comment, signature_path, Position_name) VALUES (?, ?, 'approved', ?, ?, ?)";
    $stmt_insert_action = $conn->prepare($sql_insert_action);
    if ($stmt_insert_action) {
        $stmt_insert_action->bind_param("issss", $imprest_id, $username, $comment, $signature_path, $position_name);
        if ($stmt_insert_action->execute()) {
            // Update the status of the memo in the database
            $sql_update_memo = "UPDATE imprest_safari SET status = 'approved' WHERE imprest_id = ?";
            $stmt_update_memo = $conn->prepare($sql_update_memo);
            if ($stmt_update_memo) {
                $stmt_update_memo->bind_param("i", $imprest_id);
                if ($stmt_update_memo->execute()) {
                    // Fetch approvers and email addresses
                    $sql_fetch_approvers = "SELECT Approver1, Approver2, Approver3, username FROM imprest_safari WHERE imprest_id = ?";
                    $stmt_fetch_approvers = $conn->prepare($sql_fetch_approvers);
                    if ($stmt_fetch_approvers) {
                        $stmt_fetch_approvers->bind_param("i", $imprest_id);
                        $stmt_fetch_approvers->execute();
                        $result_fetch_approvers = $stmt_fetch_approvers->get_result();
                        $row_fetch_approvers = $result_fetch_approvers->fetch_assoc();
                        $stmt_fetch_approvers->close();

                        $approver1 = $row_fetch_approvers['Approver1'];
                        $approver2 = $row_fetch_approvers['Approver2'];
                        $approver3 = $row_fetch_approvers['Approver3'];
                        $creator_username = $row_fetch_approvers['username'];

                        // Determine current approver
                        $current_approver = $_SESSION['username'];

                        // Logic to notify the creator based on current approval stage
                        $sql_fetch_creator_email = "SELECT email FROM employee_access WHERE username = ?";
                        $stmt_fetch_creator_email = $conn->prepare($sql_fetch_creator_email);
                        if ($stmt_fetch_creator_email) {
                            $stmt_fetch_creator_email->bind_param("s", $creator_username);
                            $stmt_fetch_creator_email->execute();
                            $stmt_fetch_creator_email->bind_result($creator_email);
                            $stmt_fetch_creator_email->fetch();
                            $stmt_fetch_creator_email->close();
                        }

                        if ($current_approver === $approver1) {
                            // Notify Approver2
                            if ($approver2) {
                                $sql_fetch_email = "SELECT email FROM employee_access WHERE username = ?";
                                $stmt_fetch_email = $conn->prepare($sql_fetch_email);
                                if ($stmt_fetch_email) {
                                    $stmt_fetch_email->bind_param("s", $approver2);
                                    $stmt_fetch_email->execute();
                                    $stmt_fetch_email->bind_result($email);
                                    $stmt_fetch_email->fetch();
                                    $stmt_fetch_email->close();

                                    if ($email) {
                                        $subject = "Action Required: Imprest Approval";
                                        $body = "Dear $approver2,<br><br>You have a new imprest request requiring your approval.<br>Imprest ID: $imprest_id<br>";
                                        sendEmail($email, $subject, $body);
                                    }
                                }
                                // Notify the creator about the current status
                                if ($creator_email) {
                                    $subject = "Safari Imprest Request Update";
                                    $body = "Dear $creator_username,<br><br>Your imprest request (ID: $imprest_id) has been approved by $approver1 and is now pending approval from $approver2.";
                                    sendEmail($creator_email, $subject, $body);
                                }
                            }
                        } elseif ($current_approver === $approver2) {
                            // Notify Approver3 or complete approval process if Approver3 is absent
                            if ($approver3) {
                                $sql_fetch_email = "SELECT email FROM employee_access WHERE username = ?";
                                $stmt_fetch_email = $conn->prepare($sql_fetch_email);
                                if ($stmt_fetch_email) {
                                    $stmt_fetch_email->bind_param("s", $approver3);
                                    $stmt_fetch_email->execute();
                                    $stmt_fetch_email->bind_result($email);
                                    $stmt_fetch_email->fetch();
                                    $stmt_fetch_email->close();

                                    if ($email) {
                                        $subject = "Action Required: Safari Imprest Approval";
                                        $body = "Dear $approver3,<br><br>You have a new imprest request requiring your approval.<br>Imprest ID: $imprest_id<br>";
                                        sendEmail($email, $subject, $body);
                                    }
                                }
                                // Notify the creator about the current status
                                if ($creator_email) {
                                    $subject = "Safari Imprest Request Update";
                                    $body = "Dear $creator_username,<br><br>Your imprest request (ID: $imprest_id) has been approved by $approver2 and is now pending approval from $approver3.";
                                    sendEmail($creator_email, $subject, $body);
                                }
                            } else {
                                // If no Approver3, notify the creator that the process is completed
                                if ($creator_email) {
                                    $subject = "Safari Imprest Request Completed";
                                    $body = "Dear $creator_username,<br><br>Your imprest request (ID: $imprest_id) has been fully approved by $approver1 and $approver2 and is now completed.";
                                    sendEmail($creator_email, $subject, $body);
                                }
                            }
                        } elseif ($current_approver === $approver3) {
                            // Notify the creator that the process is completed
                            if ($creator_email) {
                                $subject = "Safari Imprest Request Completed";
                                $body = "Dear $creator_username,<br><br>Your imprest request (ID: $imprest_id) has been fully approved by $approver1, $approver2, and $approver3 and is now completed.";
                                sendEmail($creator_email, $subject, $body);
                            }
                        }

                        echo json_encode(['status' => 'success']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Failed to update memo status: ' . $stmt_update_memo->error]);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare update memo statement: ' . $conn->error]);
                }
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to insert action: ' . $stmt_insert_action->error]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare insert action statement: ' . $conn->error]);
    }
}
?>
