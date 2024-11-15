<?php
include 'include.php'; // Ensure you have your DB connection here

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// assign_tickets.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the POST data
    $data = json_decode(file_get_contents('php://input'), true);
    $ticketIds = $data['ticket_ids'];
    $assignToId = $data['assign_to']; // This should be the ID

    if (empty($ticketIds) || empty($assignToId)) {
        echo json_encode(['success' => false, 'message' => 'No ticket IDs or assignee ID provided']);
        exit;
    }

    // Prepare the SQL query to update the tickets
    $ticketIdsString = implode(',', array_map('intval', $ticketIds)); // Convert ticket IDs to a string for the query
    $assignToIdEscaped = mysqli_real_escape_string($conn, $assignToId);

    $query = "UPDATE log_tickets SET assigned_to = '$assignToIdEscaped' WHERE ticket_id IN ($ticketIdsString)";

    if (mysqli_query($conn, $query)) {
        // Success, now send emails

        // Prepare query to get emails of assignee and ticket creators
        $assigneeQuery = "SELECT username FROM employee_access WHERE id = '$assignToIdEscaped'";
        $assigneeResult = mysqli_query($conn, $assigneeQuery);
        $assigneeUsername = '';
        if ($assigneeResult && $row = mysqli_fetch_assoc($assigneeResult)) {
            $assigneeUsername = $row['username'];
        }

        $assigneeEmail = '';
        if ($assigneeUsername) {
            $emailQuery = "SELECT email FROM employee_access WHERE username = '$assigneeUsername'";
            $emailResult = mysqli_query($conn, $emailQuery);
            if ($emailResult && $row = mysqli_fetch_assoc($emailResult)) {
                $assigneeEmail = $row['email'];
            }
        }

        if ($assigneeEmail) {
            // Send email to the assignee
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = '172.18.155.32'; // Set the SMTP server to send through
            $mail->SMTPAuth = true;
            $mail->Username = 'notifications@kcblbank.co.tz'; // SMTP username
            $mail->Password = 'Balancesheet@2025'; // SMTP password
            //$mail->SMTPSecure = 'tls';
            $mail->Port = 25;

            // Set email format
            $mail->setFrom('notifications@kcblbank.co.tz', 'Service Desk');
            $mail->addAddress($assigneeEmail);
            $mail->Subject = "You have been assigned a new ticket";
            $mail->Body = "You have been assigned to work on Ticket with Ticket ID: " . implode(', ', $ticketIds);

            if (!$mail->send()) {
                echo json_encode(['success' => false, 'message' => 'Failed to send assignee email.']);
                exit;
            }
        }


/*

172.18.155.32
notifications@kcblbank.co.tz
Balancesheet@2026

*/
        // Fetch email of ticket creators
        $creatorQuery = "SELECT DISTINCT ea.email, lt.username FROM log_tickets lt
                         JOIN employee_access ea ON lt.username = ea.username
                         WHERE lt.ticket_id IN ($ticketIdsString)";
        $creatorResult = mysqli_query($conn, $creatorQuery);

        while ($row = mysqli_fetch_assoc($creatorResult)) {
            $creatorEmail = $row['email'];
            $creatorUsername = $row['username'];

            if ($creatorEmail) {
                // Send email to the ticket creator
                $mailToCreator = new PHPMailer();
                $mailToCreator->isSMTP();
                $mailToCreator->Host = '172.18.155.32'; // Set the SMTP server to send through
                $mailToCreator->SMTPAuth = true;
                $mailToCreator->Username = 'notifications@kcblbank.co.tz'; // SMTP username
                $mailToCreator->Password = 'Balancesheet@2026'; // SMTP password
                //$mailToCreator->SMTPSecure = 'tls';
                $mailToCreator->Port = 25;

                $mailToCreator->setFrom('notifications@kcblbank.co.tz', 'Service Desk');
                $mailToCreator->addAddress($creatorEmail);
                $mailToCreator->Subject = "Your Service Request Ticket Assigned";
                $mailToCreator->Body = "Your Service Request Ticket with Ticket ID: " . implode(', ', $ticketIds) . " has been assigned to $assigneeUsername.";

                if (!$mailToCreator->send()) {
                    echo json_encode(['success' => false, 'message' => 'Failed to send creator email.']);
                    exit;
                }
            }
        }

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database update failed: ' . mysqli_error($conn)]);
    }

    mysqli_close($conn);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
