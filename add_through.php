<?php

session_start();
include 'include.php'; // Include your database connection
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $memo_id = $_POST['memo_id'];
    $comment = $_POST['comment'];
    $username = $_POST['username'];
    $added_by = $_SESSION['username']; // Fetch the session username

    // Check if this username has already taken action for this memo
    $checkCommentQuery = "SELECT * FROM added_through_memo_comment WHERE memo_id = ? AND username = ?";
    $stmt = $conn->prepare($checkCommentQuery);
    $stmt->bind_param("is", $memo_id, $username);
    $stmt->execute();
    $resultComment = $stmt->get_result();

    // Check if this username is already in any of the 'through' fields in the 'memos' table
    $checkThroughQuery = "
        SELECT * FROM memos 
        WHERE id = ? 
        AND (
            through = ? OR through2 = ? OR through3 = ? OR through4 = ? OR through5 = ? OR 
            through6 = ? OR through7 = ? OR through8 = ? OR through9 = ? OR through10 = ?
        )
    ";
    $stmtThrough = $conn->prepare($checkThroughQuery);
    $stmtThrough->bind_param("issssssssss", $memo_id, $username, $username, $username, $username, $username, $username, $username, $username, $username, $username);
    $stmtThrough->execute();
    $resultThrough = $stmtThrough->get_result();

    if ($resultComment->num_rows > 0 || $resultThrough->num_rows > 0) {
        // If action has already been taken or the username is found in the memos table, return a specific status
        echo json_encode(['status' => 'action_taken']);
    } else {
        // Insert the comment into the added_through_memo_comment table
        $insertQuery = "INSERT INTO added_through_memo_comment (memo_id, username, comment, added_by) VALUES (?, ?, ?, ?)";
        $stmtInsert = $conn->prepare($insertQuery);

        if ($stmtInsert) {
            $stmtInsert->bind_param("isss", $memo_id, $username, $comment, $added_by);
            if ($stmtInsert->execute()) {
                echo json_encode(['status' => 'success']);

                // Check if the username exists in employee_access
                $checkEmployeeQuery = "SELECT email FROM employee_access WHERE username = ?";
                $stmtEmployee = $conn->prepare($checkEmployeeQuery);
                $stmtEmployee->bind_param("s", $username);
                $stmtEmployee->execute();
                $resultEmployee = $stmtEmployee->get_result();
                
                //notifications@kcblbank.co.tz
                
                if ($resultEmployee->num_rows > 0) {
                    $employee = $resultEmployee->fetch_assoc();
                    $email = $employee['email'];

                    // Send email notification
                    $mail = new PHPMailer;
                    $mail->isSMTP();
                    $mail->Host = '172.18.155.32'; // Set your SMTP server
                    $mail->SMTPAuth = true;
                    $mail->Username = 'notifications@kcblbank.co.tz';
                    $mail->Password = 'Balancesheet@2026';
                    //$mail->SMTPSecure = 'tls';
                    $mail->Port = 25;

                    $mail->setFrom('notifications@kcblbank.co.tz', 'ASSIGNED MEMO');
                    $mail->addAddress($email); // Send to the user's email

                    $mail->isHTML(true);
                    $mail->Subject = "New Memo Assigned";
                    $mail->Body    = "You have been added to handle the memo with ID: $memo_id. Please check your dashboard for more details.";

                    if(!$mail->send()) {
                        echo json_encode(['status' => 'mail_error', 'error' => $mail->ErrorInfo]);
                    }
                }
                $stmtEmployee->close();
            } else {
                echo json_encode(['status' => 'error']);
            }
            $stmtInsert->close();
        } else {
            echo json_encode(['status' => 'error']);
        }
    }

    $stmt->close();
    $stmtThrough->close();
}

$conn->close();
?>
