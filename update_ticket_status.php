<?php
// Include your database connection settings
include 'include.php'; // Ensure this file sets up $conn

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the POST data
    $ticketId = isset($_POST['ticket_id']) ? intval($_POST['ticket_id']) : 0;
    $status = isset($_POST['status']) ? $_POST['status'] : '';

    if ($ticketId > 0 && !empty($status)) {
        // Prepare the SQL query to update the ticket status
        $statusEscaped = mysqli_real_escape_string($conn, $status);
        $sql = "UPDATE log_tickets SET status = ? WHERE ticket_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $statusEscaped, $ticketId);

        if ($stmt->execute()) {
            echo '<p>Status updated successfully.</p>';
            
            // Fetch the username and email of the ticket creator
            $query = "SELECT lt.username, ea.email FROM log_tickets lt 
                      JOIN employee_access ea ON lt.username = ea.username 
                      WHERE lt.ticket_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('i', $ticketId);
            $stmt->execute();
            $stmt->bind_result($username, $creatorEmail);
            $stmt->fetch();
            $stmt->close();
/*

172.18.155.32
notifications@kcblbank.co.tz
Balancesheet@2025

*/
            if ($creatorEmail) {
                // Send email to the ticket creator
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
                $mail->addAddress($creatorEmail);
                $mail->Subject = "Ticket Status Update";
                $mail->Body = "Your ticket with Ticket ID: $ticketId has been changed to $status.";

                if (!$mail->send()) {
                    echo '<p>Failed to send email notification to the ticket creator.</p>';
                }
            }

            // Redirect back to the ticket details page or display a success message
            header("Location: logged_assigned_ticket_request.php?id=$ticketId");
            exit;
        } else {
            echo '<p>Failed to update status: ' . mysqli_error($conn) . '</p>';
        }

        $stmt->close();
    } else {
        echo '<p>Invalid ticket ID or status.</p>';
    }

    $conn->close();
} else {
    echo '<p>Invalid request method.</p>';
}
?>
