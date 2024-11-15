<?php 
// Start the session
session_start();

// Check if the department_name and Position_name are set in session
if (isset($_SESSION['department_name']) && isset($_SESSION['username'])) {
    // Retrieve department_name and Position_name from session
    $department_name = $_SESSION['department_name'];
    $username = $_SESSION['username'];

    // Include database connection
    include 'include.php';

    /*Query the database to retrieve rows where username is the current user
    $sql = "SELECT m.*, e.*
                        FROM imprest_safari m
                        INNER JOIN employee_access e 
                            ON m.department_name = e.department_name 
                            AND CONCAT(e.first_name, ' ', e.last_name) = m.username
                        WHERE e.department_name = ? AND CONCAT(e.first_name, ' ', e.last_name) = ?";*/

   $sql = "SELECT m.*
                        FROM imprest_safari m
                        where username = ?";



    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result_safari = $stmt->get_result();
    $stmt->close();

    // Combine the results of username
    $result = array_merge($result_safari->fetch_all(MYSQLI_ASSOC));

    // Check if there are any matching rows
    if (!empty($result)) {
        // Output the table structure
        echo "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <link rel='shortcut icon' type='x-icon' href='KCBLLOGO.PNG'>
            <meta name='viewport' content='width=device-width, initial-scale=1'>
            <title>Memo Request</title>
            <link href='assets/css/responsive.css' rel='stylesheet' type='text/css'/>
            <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
            <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css'>
            <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>

            <style>
                    body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            max-width: 100%;
        }
                table {
            width: 98.5%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            
            overflow: hidden;
            padding-bottom: 100px;
                }

                th, td {
                    padding: 8px;
                    text-align: center;
                }

                th {
                    background-color: #3385ff;
                    color: white;
                }

                tr:nth-child(even) {
                    background-color: #f2f2f2;
                }

                tr:hover {
                    background-color: #ddd;
                }

                .add_safari {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    cursor: pointer; 
                    max-width: 99%;
                }

                .add_safari a {
                    width: 20px;
                    
                }
                .popup {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .popup-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 700px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type='text'], input[type='date'], textarea, button, input[type='file'] {
            width: 100%;
            padding: 8px;
            margin: 5px 0 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }
        textarea {
            resize: vertical;
            height: 100px;
            width: 100%;
        }
        button {
            background-color: #3385ff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 4px;
            max-width: 100px;
        }
        button:hover {
            background-color: #1e5aa6;
        }
        #file-upload-drop-area {
            border: 2px dashed #ccc;
            padding: 20px;
            text-align: center;
            cursor: pointer;
        }
        #file-upload-drop-area:hover {
            background-color: #f0f0f0;
        }
        .fa {
            font-size: 18px;
        }
        .fa-plus-circle {
            color: #3385ff;
        }
        .fa-check {
            color: green;
        }
        .fa-refresh {
            color: blue;
        }
        .fa-eye {
            color: #3385ff;
        }

            </style>
        </head>
        <body>
            <div class='add_safari'>
                <p></p>
                <a href='create_safari_imprest.php'>
                    <li class='fa fa-plus-circle' style='color: #3385ff;'></li>
                </a>
            </div>

            <table>
                <tr>
                    <th>ID</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Retire</th>
                    <th>View</th>
                </tr>
                <tbody>";

        // Iterate through each row and display data in table rows
        foreach ($result as $row) {
            echo "<tr>";
            echo "<td>" . $row["imprest_id"] . "</td>";
            //echo "<td>" . $row["username"] . "</td>";
            //echo "<td>" . $row["department_name"] . "</td>";

            // Determine status display
            $status = $row["status"];
             if (!empty($status)) {
                    if ($status == 'pending') {
                        echo '<td><span style="color: blue;">Pending</span></td>';
                    } elseif ($status == 'approved') {
                        echo '<td><span style="color: green;">Approved</span></td>';
                    } elseif ($status == 'declined') {
                        echo '<td><span style="color: red;">Declined</span></td>';
                    } else {
                        echo '<td>' . htmlspecialchars($status) . '</td>';
                    }
                } else {
                    echo '<td><span style="color: blue;">Pending</span></td>';
                }

            echo "<td>" . $row["date"] . "</td>";
                                  echo "<td style='cursor: pointer;' onclick='showPopupretire(" . json_encode($row) . ")'>";
if (($row["retirement_status"] == "Pending Retirement") && ($row["status"] == "approved")) {
    echo "<i class='bx bx-refresh bx-spin' title='Pending' style='color: blue'></i>";
} elseif (($row["status"] == "declined") || ($row["status"] == "pending")) {
echo "<span onclick='event.stopPropagation();'><i class='fa fa-remove' title='Retired' style='color: red'></i></span>";
}else{
    // code..
    // Make the icon not clickable
    echo "<span onclick='event.stopPropagation();'><i class='fa fa-check' title='Retired' style='color: green'></i></span>";
}
echo "</td>";
            echo "<td style='cursor: pointer;' onclick=\"window.location.href = 'view_safari_imprest.php?imprest_id=" . $row['imprest_id'] . "&username=" . urlencode($username) . "'\"><i class='fa fa-eye' data-toggle='tooltip' title='View' style='color: #3385ff;'></i></td>";
            echo "</tr>";
        }

        echo "</tbody>
            </table>
            <div id='popup' class='popup'>
                <div class='popup-content'>
                    <span class='close' onclick='closePopup()'>&times;</span>
                    <h2>Imprest Retirement Form</h2>
                    <form id='retirement-form' method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' enctype='multipart/form-data'>
                        <input type='hidden' id='applicant-name' name='applicant-name'>
                        <input type='hidden' id='designation' name='designation'>
                        <input type='hidden' id='department' name='department'>
                        <input type='hidden' id='claim-date' name='claim-date'>
                        <input type='hidden' id='imprest-reference-code' name='imprest-reference-code'>
                        
                        <label for='claim-nature'>Nature of Claim:</label>
                        <textarea id='claim-nature' name='claim-nature' required></textarea>

                        <label for='file-upload'>Attach File(s):</label>
                        <input type='file' id='file-upload' name='file-upload[]' multiple required ondragover='handleDragOver(event)' ondrop='handleFileDrop(event)'>
                        <div id='file-upload-drop-area' ondragover='handleDragOver(event)' ondrop='handleFileDrop(event)'>
                            Drag & Drop files here
                        </div>
                      
                        <label for='claimant-signature'>Signature of Claimant:</label>
                        <div>";

        // Fetch signature path
        require 'include.php';
        $stmt = $conn->prepare("SELECT signature_path FROM signature WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        $signature_path = '';
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $signature_path = $row['signature_path'];
        }

        $stmt->close();
        $conn->close();

        echo ($signature_path) ? "<img src='" . htmlspecialchars($signature_path) . "' alt='Signature' width='100' height='49'>" : "<p>No signature found for the user.</p>";
        echo "<input type='text' id='claimant-signature' name='claimant-signature' required readonly value='" . htmlspecialchars($signature_path) . "'>
                        </div>
                        <button type='submit'>Submit</button>
                    </form>
                </div>
            </div>
            <script>
                function addmemoreq() {
                    var amount = prompt('Enter the amount:');
                    if (amount !== null) {
                        // Proceed with adding the expenditure request
                    }
                }
            </script>
                <script>
        function showPopup(row) {
            document.getElementById('applicant-name').value = row.first_name + ' ' + row.last_name;
            document.getElementById('designation').value = row.Position_name;
            document.getElementById('department').value = row.department_name;
            document.getElementById('claim-date').value = row.date;
            document.getElementById('imprest-reference-code').value = row.imprest_id;

            document.getElementById('popup').style.display = 'block';
        }

        function closePopup() {
            document.getElementById('popup').style.display = 'none';
        }

        window.onclick = function(event) {
            var popup = document.getElementById('popup');
            if (event.target == popup) {
                popup.style.display = 'none';
            }
        }
        // Function to handle drag over event
function handleDragOver(event) {
    event.preventDefault();
    event.stopPropagation();
    event.dataTransfer.dropEffect = 'copy'; // Set the desired drop effect
}

// Function to handle file drop event
function handleFileDrop(event) {
    event.preventDefault();
    event.stopPropagation();

    var files = event.dataTransfer.files;
    var fileUpload = document.getElementById('file-upload');
    fileUpload.files = files; // Set the dropped files to the file input element
}
    </script>

</body>
</html>";
    } else {
        echo "  <style>
                table {
            width: 98.5%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            
            overflow: hidden;
            padding-bottom: 100px;
                }

                th, td {
                    padding: 8px;
                    text-align: center;
                }

                th {
                    background-color: #3385ff;
                    color: white;
                }

                tr:nth-child(even) {
                    background-color: #f2f2f2;
                }

                tr:hover {
                    background-color: #ddd;
                }

                .add_safari {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    cursor: pointer; 
                    max-width: 99%;
                }

                .add_safari a {
                    width: 20px;
                    margin-right: 30px;
                }
                .popup {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .popup-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 700px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type='text'], input[type='date'], textarea, button, input[type='file'] {
            width: 100%;
            padding: 8px;
            margin: 5px 0 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }
        textarea {
            resize: vertical;
            height: 100px;
            width: 100%;
        }
        button {
            background-color: #3385ff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 4px;
            max-width: 100px;
        }
        button:hover {
            background-color: #1e5aa6;
        }
        #file-upload-drop-area {
            border: 2px dashed #ccc;
            padding: 20px;
            text-align: center;
            cursor: pointer;
        }
        #file-upload-drop-area:hover {
            background-color: #f0f0f0;
        }
        .fa {
            font-size: 18px;
        }
        .fa-plus-circle {
            color: #3385ff;
        }
        .fa-check {
            color: green;
        }
        .fa-refresh {
            color: blue;
        }
        .fa-eye {
            color: #3385ff;
        }

            </style>
        </head>
        <body>
            <div class='add_safari'>
                <p></p>
                <a href='create_safari_imprest.php'>
                    <li class='fa fa-plus-circle' style='color: #3385ff;'></li>
                </a>
            </div>

            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Retire</th>
                    <th>View</th>
                </tr>
                <tbody>";
    }
} else {
    echo "Invalid department_name or Position_name.";
}
?>
<?php
// Start session
//session_start();

// Include file with database connection
include 'include.php';

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize variables to store form data
    $applicantName = $_POST['applicant-name'];
    $designation = $_POST['designation'];
    $department = $_POST['department'];
    $natureOfClaim = $_POST['claim-nature'];
    $claimantSignature = $_POST['claimant-signature'];
    $claimDate = $_POST['claim-date'];
    $imprestReferenceCode = $_POST['imprest-reference-code'];

    // File handling
    $uploadDir = "uploadsretire/"; // Directory where files will be uploaded
    $uploadedFiles = array();

    // Handle file uploads
    if (!empty($_FILES['file-upload']['name'][0])) {
        foreach ($_FILES['file-upload']['name'] as $key => $filename) {
            $targetFilePath = $uploadDir . basename($_FILES['file-upload']['name'][$key]);
            if (move_uploaded_file($_FILES['file-upload']['tmp_name'][$key], $targetFilePath)) {
                $uploadedFiles[] = $targetFilePath;
            } else {
                echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'File Upload Error',
                            text: 'Error uploading file $filename'
                        });
                     </script>";
                exit;
            }
        }
    }

    // Prepare SQL insert statement
    $sql = "INSERT INTO retirement (applicant_name, designation, department, nature_of_claim, claimant_signature, date, uploaded_files, retirement_status, imprest_reference_code, Approver1, Approver2) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending Retirement', ?, ?, ?)";

    // Initialize variables for Approvers
    $approver1 = null;
    $approver2 = null;

    // Check imprest_expenditure table
    $query_expenditure = "SELECT Approver1, Approver2 FROM imprest_expenditure WHERE imprest_id = ?";
    if ($stmt_expenditure = $conn->prepare($query_expenditure)) {
        $stmt_expenditure->bind_param("i", $imprestReferenceCode);
        $stmt_expenditure->execute();
        $stmt_expenditure->store_result();

        if ($stmt_expenditure->num_rows > 0) {
            $stmt_expenditure->bind_result($approver1, $approver2);
            $stmt_expenditure->fetch();
        }

        $stmt_expenditure->close();
    }

    // Check imprest_safari table if Approvers not found in imprest_expenditure
    if (!$approver1 && !$approver2) {
        $query_safari = "SELECT Approver1, Approver2 FROM imprest_safari WHERE imprest_id = ?";
        if ($stmt_safari = $conn->prepare($query_safari)) {
            $stmt_safari->bind_param("i", $imprestReferenceCode);
            $stmt_safari->execute();
            $stmt_safari->store_result();

            if ($stmt_safari->num_rows > 0) {
                $stmt_safari->bind_result($approver1, $approver2);
                $stmt_safari->fetch();
            }

            $stmt_safari->close();
        }
    }

    // Prepare and bind parameters
    $uploadedFilesStr = implode(",", $uploadedFiles); // Convert uploaded files array to string
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Database Error',
                    text: 'Error preparing statement: " . htmlspecialchars($conn->error) . "'
                });
             </script>";
        exit;
    } else {
        $stmt->bind_param("sssssssiss", $applicantName, $designation, $department, $natureOfClaim, $claimantSignature, $claimDate, $uploadedFilesStr, $imprestReferenceCode, $approver1, $approver2);

        // Execute the prepared statement
        if ($stmt->execute()) {
            $insertedId = $conn->insert_id; // Get the ID of the newly inserted record

            // Send email function to avoid redundant code
            function sendEmail($recipientEmail, $subject, $body) {
                $mail = new PHPMailer(true);
                try {
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host       = '172.18.155.32';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'notifications@kcblbank.co.tz';
                    $mail->Password   = 'Balancesheet@2026'; 
                    //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 25;

                    // Recipients
                    $mail->setFrom('notifications@kcblbank.co.tz', 'WORK FLOW SYSTEM');
                    $mail->addAddress($recipientEmail);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = $subject;
                    $mail->Body    = $body;

                    $mail->send();
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            }

            // Fetch and send email to applicant
            $sessionUsername = $_SESSION['username'];
            $query_applicant_email = "SELECT email FROM employee_access WHERE username = ?";
            if ($stmt_email = $conn->prepare($query_applicant_email)) {
                $stmt_email->bind_param("s", $sessionUsername);
                $stmt_email->execute();
                $result = $stmt_email->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $applicantEmail = $row['email'];
                    sendEmail($applicantEmail, 'Imprest Request Submitted', "Your imprest request has been submitted successfully.");
                }

                $stmt_email->close();
            }

            // Send email to Approver1
            if ($approver1) {
                $query_approver1_email = "SELECT email FROM employee_access WHERE username = ?";
                if ($stmt_email = $conn->prepare($query_approver1_email)) {
                    $stmt_email->bind_param("s", $approver1);
                    $stmt_email->execute();
                    $result = $stmt_email->get_result();

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $approver1Email = $row['email'];
                        sendEmail($approver1Email, 'Retirement Imprest Request', "You have a retirement imprest request with ID $insertedId requiring your action.");
                    }

                    $stmt_email->close();
                }
            }

            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Records inserted and emails sent successfully.'
                    }).then(function() {
                        window.location = 'dashboard.php';
                    });
                 </script>";
        } else {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Database Error',
                        text: 'Error executing statement: " . htmlspecialchars($stmt->error) . "'
                    });
                 </script>";
        }

        $stmt->close();
    }
}

// Close connection
$conn->close();
?>