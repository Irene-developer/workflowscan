<?php
session_start();
include 'include.php'; // Include your database connection
header('Content-Type: application/json');

// Include PHPMailer classes
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Function to send email using PHPMailer
 *
 * @param string $to_email Recipient's email address
 * @param string $subject Subject of the email
 * @param string $message HTML body of the email
 * @return bool Returns true if email is sent successfully, false otherwise
 */
function send_email($to_email, $subject, $message) {
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = '172.18.155.32'; // Your SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'notifications@kcblbank.co.tz'; // Your SMTP username
    $mail->Password = 'Balancesheet@2026'; // Your SMTP password (use environment variables in production)
    //$mail->SMTPSecure = 'tls';
    $mail->Port = 25;

    $mail->setFrom('notifications@kcblbank.co.tz', 'KCBL MEMOS');
    $mail->addAddress($to_email);
    $mail->isHTML(true);

    $mail->Subject = $subject;
    $mail->Body    = $message;

    // Attempt to send the email
    if (!$mail->send()) {
        // Optionally log the error: $mail->ErrorInfo
        return false;
    } else {
        return true;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize POST data
    $memoId = isset($_POST['memo_id']) ? intval($_POST['memo_id']) : 0;
    $comment = isset($_POST['comment']) ? $_POST['comment'] : '';
    $position_name = isset($_SESSION['Position_name']) ? $_SESSION['Position_name'] : '';
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

    // Basic validation
    if ($memoId <= 0 || empty($username)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request parameters.']);
        exit();
    }

    // 1. Fetch the signature path for the current user
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
        exit();
    }

    // 2. Check if the current user has already taken action on this memo
    $sql_check_action = "SELECT COUNT(*) AS action_exists FROM memo_action WHERE username = ? AND memo_id = ?";
    $stmt_check_action = $conn->prepare($sql_check_action);
    if ($stmt_check_action) {
        $stmt_check_action->bind_param("si", $username, $memoId);
        $stmt_check_action->execute();
        $result_check_action = $stmt_check_action->get_result();
        $row_check_action = $result_check_action->fetch_assoc();
        $stmt_check_action->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement to check action: ' . $conn->error]);
        exit();
    }

    if ($row_check_action['action_exists'] > 0) {
        // User has already taken action on this memo
        echo json_encode(['status' => 'action_taken']);
        exit();
    }

// 3. Increment action count in action_tracking table
$sql_increment = "INSERT INTO action_tracking (username, memo_id, action, action_count) 
                  VALUES (?, ?, ?, 1) 
                  ON DUPLICATE KEY UPDATE action_count = action_count + 1";
$stmt_increment = $conn->prepare($sql_increment);
if ($stmt_increment) {
    $stmt_increment->bind_param("sis", $username, $memoId, $action); // Bind action dynamically
    if (!$stmt_increment->execute()) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to increment action count: ' . $stmt_increment->error]);
        $stmt_increment->close();
        exit();
    }
    $stmt_increment->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement to increment action count: ' . $conn->error]);
    exit();
}


    // 4. Check action count before allowing approval
    $sql_check_count = "SELECT action_count FROM action_tracking WHERE username = ? AND memo_id = ?";
    $stmt_check_count = $conn->prepare($sql_check_count);
    if ($stmt_check_count) {
        $stmt_check_count->bind_param("si", $username, $memoId);
        $stmt_check_count->execute();
        $result_count = $stmt_check_count->get_result();
        $row_count = $result_count->fetch_assoc();
        $stmt_check_count->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement to check action count: ' . $conn->error]);
        exit();
    }

    if ($row_count['action_count'] >= 2) {
        // Action limit reached
        echo json_encode(['status' => 'action_limit_reached']);
        exit();
    }

    // 5. Update the memo status based on the selected action
    $sql_update_memo = "UPDATE memos SET status = ? WHERE id = ?";
    $stmt_update_memo = $conn->prepare($sql_update_memo);
    if ($stmt_update_memo) {
        $stmt_update_memo->bind_param("si", $action, $memoId); // Bind action as status
        if (!$stmt_update_memo->execute()) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update memo status: ' . $stmt_update_memo->error]);
            $stmt_update_memo->close();
            exit();
        }
        $stmt_update_memo->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement to update memo status: ' . $conn->error]);
        exit();
    }

    // 6. Insert details into memo_action table with the action as the status
    $sql_insert_memo_action = "INSERT INTO memo_action (memo_id, username, status, comment, signature_path, Position_name) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_insert_memo_action = $conn->prepare($sql_insert_memo_action);
    if ($stmt_insert_memo_action) {
        $stmt_insert_memo_action->bind_param("isssss", $memoId, $username, $action, $comment, $signature_path, $position_name);
        if ($stmt_insert_memo_action->execute()) {
            $stmt_insert_memo_action->close();
            //echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to insert memo action: ' . $stmt_insert_memo_action->error]);
            $stmt_insert_memo_action->close();
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement for memo_action insertion: ' . $conn->error]);
        exit();
    }

    // 7. Update the Approved_by column
    $sql_update_approved_by = "UPDATE memos SET Approved_by = ? WHERE id = ?";
    $stmt_update_approved_by = $conn->prepare($sql_update_approved_by);
    if ($stmt_update_approved_by) {
        $stmt_update_approved_by->bind_param("si", $username, $memoId);
        if (!$stmt_update_approved_by->execute()) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update Approved_by: ' . $stmt_update_approved_by->error]);
            $stmt_update_approved_by->close();
            exit();
        }
        $stmt_update_approved_by->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement to update Approved_by: ' . $conn->error]);
        exit();
    }

    // 8. Fetch memo details to determine 'to', 'From', and 'through' users
    $sql_check_to = "SELECT `to`, `From`, `through`, `through2`, `through3`, `through4`, `through5`, `through6`, `through7`, `through8`, `through9`, `through10` FROM memos WHERE id = ?";
    $stmt_check_to = $conn->prepare($sql_check_to);
    if ($stmt_check_to) {
        $stmt_check_to->bind_param("i", $memoId);
        $stmt_check_to->execute();
        $stmt_check_to->bind_result($to, $from, $through, $through2, $through3, $through4, $through5, $through6, $through7, $through8, $through9, $through10);
        $stmt_check_to->fetch();
        $stmt_check_to->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement to fetch memo details: ' . $conn->error]);
        exit();
    }

// 9. If the current user is the 'To' user, update final_approvail_of_To and notify the 'From' user
if ($username === $to) {
    // Update final_approvail_of_To
    $sql_update_final_approvail = "UPDATE memos SET final_approvail_of_To = 1 WHERE id = ?";
    $stmt_update_final_approvail = $conn->prepare($sql_update_final_approvail);
    if ($stmt_update_final_approvail) {
        $stmt_update_final_approvail->bind_param("i", $memoId);
        if (!$stmt_update_final_approvail->execute()) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update final_approvail_of_To: ' . $stmt_update_final_approvail->error]);
            $stmt_update_final_approvail->close();
            exit();
        }
        $stmt_update_final_approvail->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement to update final_approvail_of_To: ' . $conn->error]);
        exit();
    }

    // Fetch 'From' user's email
    $sql_fetch_from_email = "SELECT email FROM employee_access WHERE username = ?";
    $stmt_fetch_from_email = $conn->prepare($sql_fetch_from_email);
    if ($stmt_fetch_from_email) {
        $stmt_fetch_from_email->bind_param("s", $from);
        $stmt_fetch_from_email->execute();
        $stmt_fetch_from_email->bind_result($from_email);
        $stmt_fetch_from_email->fetch();
        $stmt_fetch_from_email->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement to fetch from email: ' . $conn->error]);
        exit();
    }

    // Send notification email to 'From' user about final approval
    if ($from_email) {
        $from_subject = "Memo Approved by Final Approver";
        $from_message = "Dear $from, your memo (ID: $memoId) has been approved by the final approver ($to) and is now ready for action.
                        <p>Please login to the system to make follow-up on the request.</p>

                        <p>Thanks & Regards,</p>
                        <p>KCBL_ICT_SUPPORT</p>
        ";
        if (!send_email($from_email, $from_subject, $from_message)) {
            // Optionally handle email failure (e.g., log it)
            // For now, we'll continue
        }
    }
}

    // 10. Notify the 'From' user about the current action
    if ($username !== $to) {
    // Fetch 'From' user's email
    $sql_fetch_from_email_notify = "SELECT email FROM employee_access WHERE username = ?";
    $stmt_fetch_from_email_notify = $conn->prepare($sql_fetch_from_email_notify);
    if ($stmt_fetch_from_email_notify) {
        $stmt_fetch_from_email_notify->bind_param("s", $from);
        $stmt_fetch_from_email_notify->execute();
        $stmt_fetch_from_email_notify->bind_result($from_email_notify);
        $stmt_fetch_from_email_notify->fetch();
        $stmt_fetch_from_email_notify->close();
    } else {
        // Handle error if needed, but continue
        $from_email_notify = null;
    }

//link to system
    $memoReviewLink = "https://localhost/access_form/login.php";


// Send notification email to 'From' user about the current action
if ($from_email_notify) {
    $notify_subject = "Memo Action Taken";
    $notify_message = "Dear $from,<br><br>"
                    . "User '$username' has approved your memo (ID: $memoId).<br><br>"
                    . "Please <a href=\"$memoReviewLink\">review the memo</a>.";

    // You can customize the message further as needed
    send_email($from_email_notify, $notify_subject, $notify_message);
}
    }

    // 11. Determine the next user to notify, ensuring they haven't already taken action
    $next_user = '';
    $through_columns = [$through, $through2, $through3, $through4, $through5, $through6, $through7, $through8, $through9, $through10];

    foreach ($through_columns as $through_user) {
        if (!empty($through_user) && $through_user !== $username) {
            // Check if this user has already taken action on this memo
            $sql_check_memo_action = "SELECT COUNT(*) AS action_taken FROM memo_action WHERE memo_id = ? AND username = ?";
            $stmt_check_memo_action = $conn->prepare($sql_check_memo_action);
            if ($stmt_check_memo_action) {
                $stmt_check_memo_action->bind_param("is", $memoId, $through_user);
                $stmt_check_memo_action->execute();
                $result_memo_action = $stmt_check_memo_action->get_result();
                $row_memo_action = $result_memo_action->fetch_assoc();
                $stmt_check_memo_action->close();
            } else {
                // If unable to prepare statement, skip this user
                continue;
            }

            if ($row_memo_action['action_taken'] == 0) {
                // This user hasn't taken action yet, set as next user
                $next_user = $through_user;
                break;
            }
        }
    }

    // If no next user found in 'through' columns, default to 'To' user (if they haven't acted)
    if (empty($next_user)) {
        // Check if 'To' user has already taken action
        $sql_check_to_action = "SELECT COUNT(*) AS action_taken FROM memo_action WHERE memo_id = ? AND username = ?";
        $stmt_check_to_action = $conn->prepare($sql_check_to_action);
        if ($stmt_check_to_action) {
            $stmt_check_to_action->bind_param("is", $memoId, $to);
            $stmt_check_to_action->execute();
            $result_to_action = $stmt_check_to_action->get_result();
            $row_to_action = $result_to_action->fetch_assoc();
            $stmt_check_to_action->close();
        } else {
            // If unable to prepare statement, assume 'To' user has acted
            $row_to_action['action_taken'] = 1;
        }

        if ($row_to_action['action_taken'] == 0) {
            $next_user = $to;
        }
    }

    // 12. Send email to the next user if one is found
    if (!empty($next_user)) {
        // Fetch email for the next user
        $sql_fetch_email = "SELECT email FROM employee_access WHERE username = ?";
        $stmt_fetch_email = $conn->prepare($sql_fetch_email);
        if ($stmt_fetch_email) {
            $stmt_fetch_email->bind_param("s", $next_user);
            $stmt_fetch_email->execute();
            $stmt_fetch_email->bind_result($next_user_email);
            $stmt_fetch_email->fetch();
            $stmt_fetch_email->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement to fetch next user email: ' . $conn->error]);
            exit();
        }

        if ($next_user_email) {
            // Send email to the next user
            $subject = "New Memo Pending Action";
            $message = "Dear $next_user,<br><br>A new memo (ID: $memoId) requires your attention. Please log in to review and take action.<br>  
                        <p>Please login to the system to make follow-up on the request.</p>

                        <p>Thanks & Regards,</p>
                        <p>KCBL_ICT_SUPPORT</p>";

            if (!send_email($next_user_email, $subject, $message)) {
                echo json_encode(['status' => 'error', 'message' => 'Failed to send email to the next user.']);
                exit();
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Next user email not found.']);
            exit();
        }
    }

    // If everything went well, respond with success
    echo json_encode(['status' => 'success']);
} else {
    // Invalid request method
    echo json_encode(['status' => 'invalid_request']);
}
?>
