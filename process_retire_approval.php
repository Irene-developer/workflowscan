<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="x-icon" href="KCBLLOGO.PNG">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retirement Approval</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        body {
            font-family: Arial;
        }
    </style>
</head>
<body>
<?php
// Start the session
session_start();

// Include database connection
include 'include.php';


            // Create PHPMailer instance
            require 'PHPMailer-master/src/Exception.php';
            require 'PHPMailer-master/src/PHPMailer.php';
            require 'PHPMailer-master/src/SMTP.php';

            use PHPMailer\PHPMailer\PHPMailer;
            use PHPMailer\PHPMailer\Exception;

// Function to sanitize input (optional but recommended)
function sanitize_input($data) {
    return htmlspecialchars(strip_tags($data));
}


// Function to send email using PHPMailer
function send_email($to, $subject, $message) {


    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();                                // Send using SMTP
        $mail->Host       = '172.18.155.32';         // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                       // Enable SMTP authentication
        $mail->Username   = 'notifications@kcblbank.co.tz';   // SMTP username
        $mail->Password   = 'Balancesheet@2026';      // SMTP password
       // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Enable TLS encryption
        $mail->Port       = 25;                        // TCP port to connect to

        //Recipients
        $mail->setFrom('notifications@kcblbank.co.tz', 'Mailer');
        $mail->addAddress($to);                         // Add a recipient

        // Content
        $mail->isHTML(true);                            // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        //echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Initialize message variable
$message = "";

// Check if the necessary POST parameters are set
if (isset($_POST['id']) && isset($_POST['username']) && isset($_POST['comments'])) {
    // Sanitize inputs
    $id = sanitize_input($_POST['id']);
    $username = sanitize_input($_POST['username']);
    $comments = sanitize_input($_POST['comments']);

    // Query the database to fetch the retirement request details
    $sql = "SELECT * FROM retirement WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a row is returned
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Check if action is not repeated for ApproveActions = 0 or 2 and comments already exist
        if (($row['ApproveActions'] == 0 && $row['ApproveActions'] == 1) && (!empty($row['comment1']) || !empty($row['comment2']))) {
            $message = "
                <script>
                    Swal.fire({
                        icon: 'info',
                        title: 'Action Skipped',
                        text: 'Action cannot be repeated as it has already been processed previously.'
                    }).then(function() {
                        window.history.back(); // Replace with your redirect URL
                    }); 
                </script>";
        } elseif (($row['ApproveActions'] == 2)) {
            $message = "
                <script>
                    Swal.fire({
                        icon: 'info',
                        title: 'Action Skipped',
                        text: 'Action cannot be repeated as it has already been processed previously.'
                    }).then(function() {
                        window.history.back(); // Replace with your redirect URL
                    }); 
                </script>";
        } else {
            // Determine if both Approvers have the same role and can act simultaneously
            if ($row['Approver1'] == $row['Approver2'] && $username == $row['Approver1']) {
                // Action taken by both Approvers
                $update_sql = "UPDATE retirement SET ApproveActions = 2, comment1 = ?, comment2 = ? WHERE id = ?";
                $stmt = $conn->prepare($update_sql);
                $stmt->bind_param("ssi", $comments, $comments, $id);
                $stmt->execute();

                // Fetch the value of ApproveActions for the specific id
                $fetch_sql = "SELECT ApproveActions, applicant_name, Approver1, Approver2 FROM retirement WHERE id = ?";
                $stmt_fetch = $conn->prepare($fetch_sql);
                $stmt_fetch->bind_param("i", $id);
                $stmt_fetch->execute();
                $result_fetch = $stmt_fetch->get_result();

                if ($result_fetch->num_rows > 0) {
                    $row = $result_fetch->fetch_assoc();
                    $ApproveActions = $row['ApproveActions'];
                    $applicantName = $row['applicant_name'];
                    $approver1 = $row['Approver1'];
                    $approver2 = $row['Approver2'];

                    // Check if ApproveActions is 2 and update retirement_status
                    if ($ApproveActions == 2) {
                        $update_status_sql = "UPDATE retirement SET retirement_status = 'retired' WHERE id = ?";
                        $stmt_status = $conn->prepare($update_status_sql);
                        $stmt_status->bind_param("i", $id);
                        $stmt_status->execute();

                        // Fetch emails for notifications
                        $query_email = "SELECT email FROM employee_access WHERE username = ?";
                        $stmt_email = $conn->prepare($query_email);

                        // Email to applicant
                        $stmt_email->bind_param("s", $applicantName);
                        $stmt_email->execute();
                        $result_email = $stmt_email->get_result();
                        if ($result_email->num_rows > 0) {
                            $email_row = $result_email->fetch_assoc();
                            $applicantEmail = $email_row['email'];

                            $subject = "Retirement Request Supervised";
                            $message = "Your retirement request has been supervised (approved) by $approver1 and is now pending action from $approver2.";
                            send_email($applicantEmail, $subject, $message);
                        }

                        // Email to Approver2
                        $stmt_email->bind_param("s", $approver2);
                        $stmt_email->execute();
                        $result_email = $stmt_email->get_result();
                        if ($result_email->num_rows > 0) {
                            $email_row = $result_email->fetch_assoc();
                            $approver2Email = $email_row['email'];

                            $subject = "Pending Retirement Request";
                            $message = "There is a pending retirement request with ID $id requiring your action.";
                            send_email($approver2Email, $subject, $message);
                        }
                    }
                } else {
                    echo "No record found with the specified ID.";
                }
            } elseif ($row['Approver1'] == $username) {
                // Action taken by Approver1
                $update_sql = "UPDATE retirement SET ApproveActions = ?, comment1 = ? WHERE id = ?";
                $approveAction = (isset($_POST['approve'])) ? 1 : ((isset($_POST['decline'])) ? 0 : 0); // If Approve, set to 1; if Decline, set to 0
                $stmt = $conn->prepare($update_sql);
                $stmt->bind_param("isi", $approveAction, $comments, $id);
                $stmt->execute();

                // Fetch applicant name and email
                $fetch_sql = "SELECT Approver2, applicant_name FROM retirement WHERE id = ?";
                $stmt_fetch = $conn->prepare($fetch_sql);
                $stmt_fetch->bind_param("i", $id);
                $stmt_fetch->execute();
                $result_fetch = $stmt_fetch->get_result();

                if ($result_fetch->num_rows > 0) {
                    $row = $result_fetch->fetch_assoc();
                    $applicantName = $row['applicant_name'];
                    $approver2 = $row['Approver2'];

                    // Fetch emails for notifications
                    $query_email = "SELECT email FROM employee_access WHERE username = ?";
                    $stmt_email = $conn->prepare($query_email);

                    // Email to applicant
                    $stmt_email->bind_param("s", $applicantName);
                    $stmt_email->execute();
                    $result_email = $stmt_email->get_result();
                    if ($result_email->num_rows > 0) {
                        $email_row = $result_email->fetch_assoc();
                        $applicantEmail = $email_row['email'];

                        if ($approveAction == 1) {
                            $subject = "Retirement Request Approved by Approver1";
                            $message = "Your retirement request has been supervised (approved) by $username and is now pending action $approver2.";
                        } else {
                            $subject = "Retirement Request Declined by Approver1";
                            $message = "Your retirement request has been declined by $username.";
                        }
                        send_email($applicantEmail, $subject, $message);
                    }

// Check if the approve action is for Approver2
if ($approveAction == 1) {
    // Prepare to bind the 'Approver2' username and fetch their email
    $stmt_email->bind_param("s", $row['Approver2']);
    $stmt_email->execute();
    
    // Fetch the result for the email
    $result_email = $stmt_email->get_result();
    
    // If there is a valid result, proceed to send the email
    if ($result_email->num_rows > 0) {
        // Fetch the email address for Approver2
        $email_row = $result_email->fetch_assoc();
        $approver2Email = $email_row['email'];
        
        // Prepare the email subject and message
        $subject = "Pending Retirement Request";
        $message = "Dear $approver2,\n\nThere is a pending retirement request with ID $id requiring your action.\n\nPlease log in to the system and take necessary steps.";
        
        // Send the email to Approver2
        send_email($approver2Email, $subject, $message);
        
        // Optionally, log success
        //echo "Email sent to Approver2: $approver2Email";
    } else {
        // If no email found for Approver2, handle the error (optional logging)
        echo "Could not find email for Approver2.";
    }
}

                } else {
                    echo "No record found with the specified ID.";
                }
            } elseif ($row['Approver2'] == $username) {
                // Action taken by Approver2
                $update_sql = "UPDATE retirement SET ApproveActions = ?, comment2 = ? WHERE id = ?";
                $approveAction = (isset($_POST['approve'])) ? 2 : ((isset($_POST['decline'])) ? 0 : 0); // If Approve, set to 2; if Decline, set to 0
                $stmt = $conn->prepare($update_sql);
                $stmt->bind_param("isi", $approveAction, $comments, $id);
                $stmt->execute();

                // Fetch applicant name and email
                $fetch_sql = "SELECT applicant_name FROM retirement WHERE id = ?";
                $stmt_fetch = $conn->prepare($fetch_sql);
                $stmt_fetch->bind_param("i", $id);
                $stmt_fetch->execute();
                $result_fetch = $stmt_fetch->get_result();

                if ($result_fetch->num_rows > 0) {
                    $row = $result_fetch->fetch_assoc();
                    $applicantName = $row['applicant_name'];

                    // Fetch emails for notifications
                    $query_email = "SELECT email FROM employee_access WHERE username = ?";
                    $stmt_email = $conn->prepare($query_email);

                    // Email to applicant
                    $stmt_email->bind_param("s", $applicantName);
                    $stmt_email->execute();
                    $result_email = $stmt_email->get_result();
                    if ($result_email->num_rows > 0) {
                        $email_row = $result_email->fetch_assoc();
                        $applicantEmail = $email_row['email'];

                        if ($approveAction == 2) {
                            $subject = "Retirement Request Completed";
                            $message = "Your retirement request has been supervised (approved) by $username and is now completed.";
                        } else {
                            $subject = "Retirement Request Declined by Approver2";
                            $message = "Your retirement request has been declined by $username.";
                        }
                        send_email($applicantEmail, $subject, $message);
                    }
                } else {
                    echo "No record found with the specified ID.";
                }
            } else {
                $message = "
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Approver',
                            text: 'You are not authorized to approve or decline this request.'
                        }).then(function() {
                            window.history.back();
                        });
                    </script>";
            }

            // Execute the update statement if not skipped
            if ($stmt->execute()) {
                // Update successful
                $message = "
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Approval process updated successfully.'
                        }).then(function() {
                            window.history.back(); // Replace with your redirect URL
                        }); 
                    </script>";
            } else {
                // Update failed
                $message = "
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to update approval process. Please try again.'
                        }).then(function() {
                            window.history.back();
                        });
                    </script>";
            }
        }
    } else {
        $message = "
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Not Found',
                    text: 'No retirement request found with ID $id.'
                }).then(function() {
                    window.history.back();
                });
            </script>";
    }

    // Close statement
    $stmt->close();

    // Close connection
    $conn->close();

    // Echo the message
    echo $message;
} else {
    $message = "
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Invalid Parameters',
                text: 'Required parameters are missing.'
            }).then(function() {
                window.history.back();
            });
        </script>";
    echo $message;
}
?>
</body>
</html>
