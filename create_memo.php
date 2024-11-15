<?php
// Start the PHP session
include 'include.php';

//session_start();
include('session_timeout.php');
// Check if the username is set in the session
if(isset($_SESSION['username']) && isset($_SESSION['department_name']) && isset($_SESSION['Position_name'])) {
    // If username is set, retrieve and display it
    $username = $_SESSION['username'];
    $department_name=$_SESSION['department_name'];
    $position_name=$_SESSION['Position_name'];
    
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="x-icon" href="KCBLLOGO.PNG">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Memo</title>
    <!-- Stylesheets -->
    
    <link rel="stylesheet" href="assets/fas fas fas 3/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/quill/quill.snow.css">
    <!-- Scripts -->
    <script src="assets/quill/quill.min.js"></script>
    <script src="assets/js/jquery/jquery-3.7.1.min.js"></script>
    <script src="assets/sweetalert2/sweetalert2.all.min.js"></script>
    
    
    
<style>
    
body {
    font-family: "Open Sans", Arial, "Helvetica Neue", Helvetica, "Segoe UI", Roboto, "Droid Sans", "Fira Sans", "Lato", "Noto Sans", "PT Sans", "Ubuntu", Cantarell, "Gill Sans", "Lucida Grande", Tahoma, Verdana, "Geneva", "Trebuchet MS", "Century Gothic", "Franklin Gothic Medium", "Lucida Sans Unicode", "Arial Black", "Impact", sans-serif, "Courier New", Courier, "Lucida Console", Monaco, "Andale Mono", monospace, Georgia, "Times New Roman", Times, serif, "Palatino Linotype", "Book Antiqua", "MS Serif", "Comic Sans MS", "Comic Sans", cursive;
    background-color: #f4f4f4;
    margin: 0; /* Remove default margin */
    padding-left: 80;
    padding-bottom: 50px;
    max-width: 100%;
}



h1 {
    text-align: center;
    color: #333;
}

form {
    margin-top: 20px;
}

.form-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    margin-left: 13px;
}

.form-header label {
    flex: 1;
    margin-right: 10px;
}

.form-header input {
    flex: 2;
    width: 100%;
}

input[type="number"], input[type="text"], textarea {
    width: calc(100% - 12px);
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    transition: border-color 0.3s ease;
}

input[type="number"]:focus, input[type="text"]:focus, textarea:focus {
    outline: none;
    border-color: #3385ff;
}

textarea {
    resize: vertical;
}

input[type="submit"] {
    width: 100%;
    padding: 10px;
    background-color: #3385ff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

input[type="submit"]:hover {
    background-color: #0056b3;
}

/* Dropdown container */
.dropdown {
    position: relative;
    display: inline-block;
    width: 200px;
}

/* Dropdown input */
.dropdown input[type="text"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    cursor: pointer;
}

/* Dropdown content (hidden by default) */
.dropdown-content, #Toinput {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
    z-index: 1;
}

/* Links inside the dropdown */
.dropdown-content a, #Toinput a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

/* Change color of links on hover */
.dropdown-content a:hover, #Toinput a:hover {
    background-color: #f1f1f1;
}

/* Show the dropdown menu on click */
.dropdown input[type="text"]:focus + .dropdown-content, #To:focus + #Toinput {
    display: block;
}

/* Dropdown style */
select {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    width: 100%;
    font-size: 16px;
}

/* Optional: Style for the label */
label {
    font-weight: bold;
    margin-right: 10px;
}

/* CSS styles for the Purpose of Expenditure Imprest section */
.purpose-section {
    margin-top: 20px;
}

.purpose-section h3 {
    color: #3385ff;
    font-size: 1.2em;
    margin-bottom: 10px;
}

.purpose-section textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    resize: vertical;
}

.purpose-section textarea:focus {
    outline: none;
    border-color: #3385ff;
}

.purpose-section textarea::placeholder {
    color: #999;
}

.select-container {
    position: relative;
    display: inline-block;
}

.select {
    width: 200px;
    padding: 10px;
    font-size: 16px;
    border: 2px solid #ccc;
    border-radius: 5px;
    appearance: none;
    background-image: url('data:image/svg+xml;utf8,<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>');
    background-repeat: no-repeat;
    background-position: right 10px top 50%;
    background-size: 20px;
}

.select:focus {
    outline: none;
    border-color: #007bff;
}

.select-container i {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #555;
}

/* Table styles */
.table-container {
    width: 100%;
    border-collapse: collapse;
    border: 2px solid #3385ff;
    margin-bottom: 20px;
}

.table-container th, .table-container td {
    border: 1px solid #3385ff;
    padding: 8px;
}

.table-container th {
    background-color: #3385ff;
    color: #fff;
    text-align: center;
}

.table-container input[type="text"], .table-container input[type="number"], .table-container input[type="date"] {
    width: calc(100% - 20px);
    padding: 8px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.table-container input[type="text"]:focus, .table-container input[type="number"]:focus, .table-container input[type="date"]::focus {
    outline: none;
    border-color: #3385ff;
}

.table-container input[type="text"]::placeholder, .table-container input[type="number"]::placeholder, .table-container input[type="date"]::placeholder {
    color: #999;
}

.table-container input[type="text"]:invalid, .table-container input[type="number"]:invalid, .table-container input[type="date"]::invalid {
    border-color: red;
}

.table-container input[type="text"]:valid, .table-container input[type="number"]:valid, .table-container input[type="date"]::valid {
    border-color: green;
}

/* Button styles */
button[type="submit"] {
    padding: 10px 20px;
    background-color: #3385ff;
    border: none;
    color: #fff;
    cursor: pointer;
    border-radius: 4px;
    margin-top: 20px;
}

button[type="submit"]:hover {
    background-color: #1a73e8;
}

/* Signature and Date section styles */
.container-signdate {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
    margin-left: 100px;
    margin-right: 100px;
}

.container-signdate h3 {
    flex: 1;
    margin: 0;
}

.container-signdate input[type="text"], .container-signdate input[type="date"] {
    flex: 1;
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    margin-left: 10px;
}

.container-signdate input[type="text"]:focus, .container-signdate input[type="date"]:focus {
    outline: none;
    border-color: #3385ff;
}

.container-signdate input[type="text"]::placeholder, .container-signdate input[type="date"]::placeholder {
    color: #999;
}

/* Container styles */
.container-headsection {
    display: flex;
    align-items: center;
    padding: 20px;
    width: 120%;
}

.container-image {
    border-left: solid;
    border-color: black;
    max-width: 20%;
}

.container-textheadsection {
    padding-right: 10px;
}

/* Image styles */
img {
    width: 200px;
    height: auto;
    margin-right: 30px;
    padding: 10px;
}

/* Styling for the paragraph containing the outstanding Imprest */
.outstanding-imprest {
    font-size: 16px;
    margin-top: 20px;
    color: #666;
}

.outstanding-imprest input {
    width: 100px;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-left: 5px;
}
.custom-dropdown {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    transition: all 0.3s ease-in-out;
    transform-origin: top;
    transform: scaleY(0);
    opacity: 0;
}

.custom-dropdown.show {
    transform: scaleY(1);
    opacity: 1;
}

.custom-dropdown select {
    margin-right: 10px;
    padding: 5px;
    border-radius: 5px;
    border: 1px solid #ccc;
    transition: all 0.3s ease;
}

.custom-dropdown .dropdown-toggle,
.custom-dropdown .remove-dropdown-toggle {
    cursor: pointer;
    font-size: 20px;
    color:#3385ff;
    transition: color 0.3s ease;
}

.custom-dropdown .dropdown-toggle:hover,
.custom-dropdown .remove-dropdown-toggle:hover {
    color: #3385ff;
}
.hidden {
        display: none;
    }
    .hidden {
        display: none;
    }
    .container {
    max-width: 96%;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}
    .hidden {
        display: none;
    }
    .container {
    max-width:100%;
    margin-top: 0px;
    background-color: #fff;
    padding: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}
/* General Header Styles */
header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #fff; /* Light background for header */
    padding: 20px;
    /* border-bottom: 2px solid #3385ff; Border at the bottom of the header */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
}

/* Logo Section */
.logo-section img {
    height: 50px; /* Adjust based on the size of your logo */
    width: auto;
}

/* Header Title */
header h1 {
    color: #003cb3; /* Header title color */
    margin: 0;
    font-size: 24px; /* Adjust font size as needed */
    text-align: center;
    flex: 1; /* Allow title to take available space */
}

/* Send Request Section */
.send-request a {
    display: inline-block;
    padding: 10px 20px;
    background-color: #fff; /* Background color for the button */
    color: #fff; /* Text color for the button */
    text-decoration: none;
    border-radius: 5px; /* Rounded corners */
    font-size: 16px; /* Font size for the button */
    transition: background-color 0.3s ease; /* Smooth color transition on hover */
}

.send-request a:hover {
    background-color: #002a80; /* Darker shade on hover */
}


          #editor {
            height: 300px;
        }
        .ql-font-serif {
            font-family: serif;
        }
        .ql-font-monospace {
            font-family: monospace;
        }
        .ql-font-arial {
            font-family: Arial, sans-serif;
        }
        .ql-font-courier {
            font-family: Courier, monospace;
        }
        .ql-font-georgia {
            font-family: Georgia, serif;
        }
        .ql-font-helvetica {
            font-family: Helvetica, sans-serif;
        }
        .ql-font-lucida {
            font-family: "Lucida Console", monospace;
        }
        .ql-font-tahoma {
            font-family: Tahoma, sans-serif;
        }
        .ql-font-times {
            font-family: "Times New Roman", serif;
        }
        .ql-font-trebuchet {
            font-family: "Trebuchet MS", sans-serif;
        }
        .ql-font-verdana {
            font-family: Verdana, sans-serif;
        }


.create_memo_header {
    display: flex;
   
    align-items: center;
    align-content: center;
    border-bottom: solid;
    border-color: black;
    padding: 10px;
    max-width: 100%;
}
.create_memo_header_img{
align-items: center;
align-content: center;
margin-left: 190px;
width: 45%;
border-right: solid;
border-color: black;
}
.create_memo_header_h1 {
    align-items: center;
    width: 55%;
}
.memo-attachment {
    padding: 10px;
    background-color: #f9f9f9; /* Light grey background */
    border: 1px solid #ddd; /* Light border */
    border-radius: 5px; /* Rounded corners */
    width: fit-content; /* Adjust width to content */
    margin: 10px 0; /* Spacing around the div */
}

.memo-attachment input[type="file"] {
    padding: 5px; /* Space inside the input */
    border: 1px solid #ccc; /* Border around the input */
    border-radius: 3px; /* Rounded corners for the input */
    background-color: #fff; /* White background */
    font-family: Arial, sans-serif; /* Font style */
    font-size: 14px; /* Font size */
    cursor: pointer; /* Change cursor to pointer */
}

.memo-attachment input[type="file"]::-webkit-file-upload-button {
    background: #007bff; /* Button background color */
    color: white; /* Button text color */
    padding: 5px 10px; /* Button padding */
    border: none; /* Remove button border */
    border-radius: 3px; /* Rounded corners for button */
    cursor: pointer; /* Change cursor to pointer */
}

.memo-attachment input[type="file"]::-webkit-file-upload-button:hover {
    background: #0056b3; /* Darker button background on hover */
}

.memo-attachment br {
    display: none; /* Remove line breaks */
}

</style>
</head>
<body>

     <header>
<div class="logo-section">
    <a href="dashboard.php">
        <img src="KCBLLOGO.png" alt="Your Logo" style="cursor: pointer;">
    </a>
</div>
         <h1 style="color: #003cb3;">CREATE MEMO</h1>
        <div class="send-request">
            <a href="sendrequest.php" class="hidden"></i></a>
        </div>
    </header>

<?php
// Start the PHP session
//session_start();

// Check if the username is set in the session
if (isset($_SESSION['username']) && isset($_SESSION['department_name']) && isset($_SESSION['Position_name'])) {
    $username = $_SESSION['username'];
    $department_name = $_SESSION['department_name'];
    $position_name = $_SESSION['Position_name'];
} else {
    die('Session variables are not set.');
}

require 'E:/xampp/htdocs/SERVICENET/PHPMailer-master/src/Exception.php';
require 'E:/xampp/htdocs/SERVICENET/PHPMailer-master/src/PHPMailer.php';
require 'E:/xampp/htdocs/SERVICENET/PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'include.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $date = $_POST['date'];
    $departmentName = $_POST['departmentName'];
    $refNo = $_POST['refNo'];
    $classification = $_POST['classfication'];
    $to = $_POST['To'];
    $from = $_POST['from'];
    $subject = $_POST['subject'];
    $content = $_POST['content'];
    $signature_path = $_POST['signature_url'];
    $throughValues = isset($_POST['through']) ? $_POST['through'] : [];

    // Initialize file upload variables
    $uploadDir = 'uploads_memos/';
    $uploadedFilePath = '';

    // Check if a file was uploaded
    if (isset($_FILES['fileUpload']) && $_FILES['fileUpload']['error'] == 0) {
        $fileName = $_FILES['fileUpload']['name'];
        $fileTmpName = $_FILES['fileUpload']['tmp_name'];
        $fileSize = $_FILES['fileUpload']['size'];

        // Validate file size (optional)
        if ($fileSize > 10000000) { // Example: limit file size to 10MB
            echo "File is too large.";
            exit;
        }

        // Create the upload directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Move the file to the upload directory
        $uploadedFilePath = $uploadDir . basename($fileName);
        if (move_uploaded_file($fileTmpName, $uploadedFilePath)) {
            // File uploaded successfully
        } else {
            echo "Failed to upload file.";
            exit;
        }
    } else {
        //echo "No file uploaded or there was an error.";
        //exit;
    }

    // Build the SQL query dynamically
    $columns = ["username", "date", "departmentName", "refNo", "classfication", "signature_path", "`to`", "`from`", "subject", "content", "`through`", "file_path"];
    $values = [$username, $date, $departmentName, $refNo, $classification, $signature_path, $to, $from, $subject, $content, count($throughValues) > 0 ? $throughValues[0] : '', $uploadedFilePath];

    for ($i = 1; $i < count($throughValues); $i++) {
        $columns[] = "through" . ($i + 1);
        $values[] = $throughValues[$i];
    }

    $columnsStr = implode(", ", $columns);
    $placeholders = implode(", ", array_fill(0, count($values), "?"));

    $sql = "INSERT INTO memos ($columnsStr) VALUES ($placeholders)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Error preparing the SQL statement: ' . $conn->error);
    }

    $stmt->bind_param(str_repeat('s', count($values)), ...$values);

    if ($stmt->execute()) {
        // After successful insertion, send emails

        // Fetch email address for the creator
        $creatorEmailQuery = "SELECT email FROM employee_access WHERE username = ?";
        $creatorEmailStmt = $conn->prepare($creatorEmailQuery);
        $creatorEmailStmt->bind_param("s", $username);
        $creatorEmailStmt->execute();
        $creatorEmailResult = $creatorEmailStmt->get_result();

        if ($creatorEmailResult->num_rows > 0) {
            $row = $creatorEmailResult->fetch_assoc();
            $creatorEmail = $row['email'];
        } else {
            $creatorEmail = '';
        }
/*
172.18.155.32
notifications@kcblbank.co.tz
Balancesheet@2026
*/
        // Create PHPMailer instance
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();                                        // Send using SMTP
            $mail->Host       = '172.18.155.32';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                               // Enable SMTP authentication
            $mail->Username   = 'notifications@kcblbank.co.tz';     // SMTP username
            $mail->Password   = 'Balancesheet@2026';               // SMTP password
            //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;     // Enable TLS encryption
            $mail->Port       = 25;                                 // TCP port to connect to

            // Base URL of the system
            $baseURL = 'http://192.168.10.66:8080/SERVICENET/login.php'; // Adjust this to your base URL

            // Build memo review link
            $memoReviewLink = $baseURL . '?memo_id=' . $conn->insert_id;

            // Recipients
            if (!empty($creatorEmail)) {
                $mail->setFrom('notifications@kcblbank.co.tz', 'CBT SERVICENET');
                $mail->addAddress($creatorEmail);                     // Add the creator email
                $mail->addReplyTo('notifications@kcblbank.co.tz', 'Information');
                
                // Email content
                $mail->isHTML(true);
                $mail->Subject = 'New Memo Created';
                $mail->Body    = "A new memo has been created.<br><br>"
                                . "Subject: $subject<br>"
                                . "From: $from<br>"
                                //. "To: $to<br>"
                                . "Date: $date<br>"
                                . "Please review the memo at your earliest convenience by clicking the link below:<br>"
                                . "<a href=\"$memoReviewLink\">Review Memo</a>";
                $mail->send();
            }

            // Send email to recipient in `through` field (only the first `through` value)
            if (isset($throughValues[0]) && !empty($throughValues[0])) {
                $recipientUsername = $throughValues[0];

                // Fetch email address associated with the username
                $emailQuery = "SELECT email FROM employee_access WHERE username = ?";
                $emailStmt = $conn->prepare($emailQuery);
                $emailStmt->bind_param("s", $recipientUsername);
                $emailStmt->execute();
                $emailResult = $emailStmt->get_result();

                if ($emailResult->num_rows > 0) {
                    $row = $emailResult->fetch_assoc();
                    $recipientEmail = $row['email'];

                    // Send email using PHPMailer
                    $mail->clearAddresses(); // Clear previous addresses
                    $mail->addAddress($recipientEmail); // Add new recipient

                    // Email content
                    $mail->isHTML(true); // Ensure email is in HTML format
                    $mail->Subject = 'New Memo Notification';
                    $mail->Body    = "A new memo has been created.<br><br>"
                                    . "Subject: $subject<br>"
                                    . "From: $from<br>"
                                    //. "To: $to<br>"
                                    . "Date: $date<br>"
                                    . "Please review the memo at your earliest convenience by clicking the link below for your actions:<br>"
                                    . "<a href=\"$memoReviewLink\">Review Memo</a>";

                    $mail->send();
                    //echo "Notification sent to $recipientUsername at $recipientEmail<br>";
                } else {
                    echo "No email found for username: $recipientUsername<br>";
                }

                $emailStmt->close();
            } else {
                echo "No recipient specified in `through` field.<br>";
            }

            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Memo Created successfully',
                    showConfirmButton: false,
                    timer: 1500
                }).then(function () {
                    // Optionally, redirect or reload the page
                });
            </script>";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    } else {
        echo "Error executing query: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>





    <div class="container">
       
        <form action="" method="POST" class="form-show" enctype="multipart/form-data">

            <div style="display: flex; justify-content: space-between;" class="hidden" >
               
            <div class="form-header hidden">
            <label for="username">Name</label>
            <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
            </div>

            <div class="form-header hidden">
                <label for="departmentName">Department</label>
                <input type="text" id="departmentName" name="departmentName" value="<?php echo $department_name; ?>" required class="hidden">
            </div>

            </div></br>

           <div style="display: flex; justify-content: space-between; border-bottom: solid; border-color: black; margin-top: 17px;">
    <div class="form-header">
        <?php
        include 'include.php';
        
function generateRefNo($length = 5) {
    $characters = '0123456789';
    $refNo = '';
    $maxIndex = strlen($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $refNo .= $characters[rand(0, $maxIndex)];
    }
    return $refNo;
}
?>

    <label for="refNo">RefNo:</label>
    <input type="text" id="refNo" name="refNo" value="<?php echo generateRefNo(); ?>" required readonly>
</div>


    <div class="form-header">
        <label for="date">Date</label>
        <input type="text" id="date" name="date" required readonly>
    </div>


    <div class="form-header">
        <label for="classfication">Classfication</label>
        <select id="classfication" name="classfication" required>
            <option value="">Select origin</option>
            <option value="Internal Memo">Internal Memo</option>
            <option value="Open">Open</option>
            <option value="Confidential">Confidential</option>
            <!-- Add more options as needed -->
        </select>
    </div>
</div>

<div style="display: flex; justify-content: space-between; border-bottom: solid; border-color: black; margin-top: 17px;">

    <div class="form-header" style="margin-left: 13px;">
        <label for="To">To</label>
        <select id="To" name="To" required>
            <option value="">Select Position</option>
            <?php
include 'include.php';

/// Assuming $_SESSION['position_name'] holds the position name of the current session
if(isset($_SESSION['Position_name'])) {

    $currentPositionName = $_SESSION['Position_name'];
    // Query to fetch unique position names from the database, excluding the current session's position name
    //$sql = "SELECT DISTINCT Position_name FROM position WHERE Position_name != '$currentPositionName'";
    $sql = "SELECT p.Position_name, e.name, e.username 
                        FROM position p 
                        LEFT JOIN employee_access e 
                        ON p.position_id = e.position_id 
                        WHERE p.Position_name != '$currentPositionName'";
    $result = $conn->query($sql);

    // Populate dropdown with fetched data
   if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Display both Position_name and name
                        echo "<option value='" . $row["username"] . "'>" . 
                             $row["username"] . " - " . 
                             (isset($row["name"]) ? $row["name"] : 'No name') . " - " . 
                             (isset($row["Position_name"]) ? $row["Position_name"] : 'No Username') . 
                             "</option>";
                    }
                } else {
        echo "<option value=''>No positions found</option>";
    }
}
$conn->close();
?>

        </select>
    </div>


<div class="form-header">
    <div id="dropdown-container">
        <label for="through">UFS</label>
        <select id="through" name="through[]" required>
            <option value="">Select Position</option>
            <?php
            include 'include.php';

            if (isset($_SESSION['Position_name'])) {
                $currentPositionName = $_SESSION['Position_name'];
                // Modify the SQL query to include the `name` field
                $sql = "SELECT p.Position_name, e.name, e.username 
                        FROM position p 
                        LEFT JOIN employee_access e 
                        ON p.position_id = e.position_id 
                        WHERE p.Position_name != '$currentPositionName'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Display both Position_name and name
                        echo "<option value='" . $row["username"] . "'>" . 
                             $row["username"] . " - " . 
                             (isset($row["name"]) ? $row["name"] : 'No name') ." : " . 
                             (isset($row["Position_name"]) ? $row["Position_name"] : 'No Username') .  
                             "</option>";
                    }
                } else {
                    echo "<option value=''>No positions found</option>";
                }
            }
            $conn->close();
            ?>
        </select>
        <a href="#" class="dropdown-toggle">
            <i class="fa fa-plus-circle"></i>
        </a>
    </div>
</div>





    

 <!--div class="form-header">
        <label for="ufs">UFS</label>
        <select id="ufs" name="ufs" required>
            <--?php
include 'include.php';

// Query to fetch unique position names from the database
$sql = "SELECT DISTINCT position_name FROM position";
$result = $conn->query($sql);

// Populate dropdown with fetched data
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<option value='" . $row["position_name"] . "'>" . $row["position_name"] . "</option>";
    }
} else {
    echo "<option value=''>No positions found</option>";
}
$conn->close();
?>
        </select>
    </div-->


    <div class="form-header hidden">
        <label for="from">From</label>
         <?php
         /*

include 'include.php';

// Query to fetch unique position names from the database
$sql = "SELECT DISTINCT position_name FROM position";
$result = $conn->query($sql);

$conn->close();
*/
?>

        <input type="text" id="from" name="from" value="<?php echo $username; ?>" required readonly class="hidden">
    </div>
   
</div>

<div style=" 
    margin: 10px;
">
     <label for="subject">Subject:</label>
<input type="text" id="subject" name="subject" required oninput="capitalizeAndBold(this)"></br></br>
</div>

<label style="margin: 10px;">Contents</label>
<textarea id="editor" name="content" style="margin: 10px;">
    <?php echo isset($memo['content']) ? htmlspecialchars_decode($memo['content']) : ''; ?>
</textarea>

<div>
        <h3 id="signature-trigger">Signature <i class="fas fa-signature" style="color: green;"></i>
</h3>
        <div id="signature-image-container"></div>
        <div id="signature-url"></div> <!-- Added this div to display the signature URL -->
</div>
        
<div class="memo-attachment">
        <input type="file" name="fileUpload" />   
</div>          

            <input type="submit" value="Submit Memo">

        </form>
</div>
<script>
    function capitalizeAndBold(input) {
        // Convert input value to uppercase
        input.value = input.value.toUpperCase();
        
        // Set font weight to bold
        input.style.fontWeight = 'bold';
    }
</script>
    <script>
        // Function to get the current date and time in the format YYYY-MM-DD HH:MM:SS
        function getCurrentDateTime() {
            var now = new Date();
            var seconds = String(now.getSeconds()).padStart(2, '0');
            var minutes = String(now.getMinutes()).padStart(2, '0');
            var hours = String(now.getHours()).padStart(2, '0');
            var month = String(now.getMonth() + 1).padStart(2, '0');
            var day = String(now.getDate()).padStart(2, '0');
            var year = now.getFullYear();
            return year + '-' + month + '-' + day + ' ' + hours + ':' + minutes + ':' + seconds;
        }

        // Set the value of the date input field to the current date and time
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('date').value = getCurrentDateTime();
        });
    </script>
<script src="tinymce/js/tinymce/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#editor',
        plugins: 'table advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste code help wordcount',
        toolbar: 'undo redo | fontselect fontsizeselect formatselect | ' +
                 'bold italic underline strikethrough | forecolor backcolor | ' +
                 'alignleft aligncenter alignright alignjustify | ' +
                 'bullist numlist outdent indent | removeformat | help | ' +
                 'table | link image media',
        menubar: 'file edit view insert format tools table help',
        font_formats: 'Arial=arial,helvetica,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Times New Roman=times new roman,times;Trebuchet=trebuchet ms,geneva;Verdana=verdana,geneva;Century Gothic=Century Gothic;Calibri=calibri;',
        setup: function (editor) {
            editor.on('change', function () {
                editor.save(); // Save content to the textarea on change
            });
        },
        height: 300
    });
</script>
<script>
$(document).ready(function() {
    var throughCount = 1; // Initial count of through fields

    $('.dropdown-toggle').click(function(e) {
        e.preventDefault();

        // Increment the count and create a new dropdown
        throughCount++;
        var newLabel = $('<label>').attr('for', 'through' + throughCount).text('UFS');
        var newSelect = $('<select>').attr('id', 'through' + throughCount).attr('name', 'through[]').attr('required', true);
        
        // Add a minus icon to remove the dropdown
        var removeIcon = $('<a href="#" class="remove-dropdown"><i class="fa fa-minus-circle"></i></a>');

        // Fetch data using AJAX to populate the dropdown
        $.ajax({
            url: 'fetch_select.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // Clear previous options
                newSelect.empty();

                // Add a default option
                newSelect.append($('<option>').text('Select Position').attr('value', ''));

                // Populate the dropdown with Position_name and name
                $.each(response, function(index, item) {
                    var optionText = item.username + ' - ' + item.name + ' : ' + item.Position_name;
                    var option = $('<option>').attr('value', item.username).text(optionText);
                    newSelect.append(option);
                });
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error);
            }
        });

        // Create a container for the label, select, and remove icon
        var dropdownGroup = $('<div class="dropdown-group"></div>');
        dropdownGroup.append(newLabel);
        dropdownGroup.append(newSelect);
        dropdownGroup.append(removeIcon);

        // Append the new group to the container
        $('#dropdown-container').append(dropdownGroup);
    });

    // Event delegation to handle the removal of dynamically added dropdowns
    $('#dropdown-container').on('click', '.remove-dropdown', function(e) {
        e.preventDefault();
        $(this).closest('.dropdown-group').remove(); // Remove the entire group (label, select, and icon)
    });
});
</script>

<script>
    document.getElementById('signature-trigger').addEventListener('click', function() {
        fetchSignature();
    });

    function fetchSignature() {
        // Perform an AJAX request to fetch the signature
        // Replace 'get_signature.php' with the appropriate PHP script that handles fetching the signature
        fetch('get_signature.php', {
            method: 'POST',
            credentials: 'same-origin', // Include cookies in the request
        })
        .then(response => response.text())
        .then(signaturePath => {
            // Display the signature image
            displaySignatureImage(signaturePath);
        })
        .catch(error => console.error('Error fetching signature:', error));
    }

    function displaySignatureImage(signaturePath) {
        // Create an <img> element
        var img = document.createElement('img');
        img.src = signaturePath; // Set the image source to the fetched signature path
        img.style.maxWidth = '40px'; // Set max width for the image

        // Get the signature image container
        var signatureContainer = document.getElementById('signature-image-container');

        // Append the image to the container
        signatureContainer.innerHTML = ''; // Clear any existing content
        signatureContainer.appendChild(img);
    }
</script>
<script>
    // Function to fetch the signature URL
    function fetchSignatureURL() {
        // Perform an AJAX request to fetch the signature URL
        fetch('get_signature_url.php', {
            method: 'POST',
            credentials: 'same-origin', // Include cookies in the request
        })
        .then(response => response.text())
        .then(signatureURL => {
            // Insert the signature URL into a hidden input field in the form
            var signatureInput = document.createElement('input');
            signatureInput.type = 'hidden';
            signatureInput.name = 'signature_url';
            signatureInput.value = signatureURL;

            // Append the hidden input field to the form
            document.querySelector('form').appendChild(signatureInput);
        })
        .catch(error => console.error('Error fetching signature URL:', error));
    }

    // Check if the username in the session matches the username in the table
    var sessionUsername = "<?php echo $username; ?>"; // Get the session username from PHP
    var tableUsername = "<?php echo $username; ?>"; // Get the username from the table from PHP

    if (sessionUsername === tableUsername) {
        // If the usernames match, fetch the signature URL
        fetchSignatureURL();
    }
</script>
<script>
        document.getElementById('memoForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent the form from submitting

            // Get form data
            const username = document.getElementById('username').value;
            const departmentName = document.getElementById('departmentName').value;
            const date = document.getElementById('date').value;
            const subject = document.getElementById('subject').value;
            const content = document.getElementById('content').value;

            // Store form data in local storage
            localStorage.setItem('username', username);
            localStorage.setItem('departmentName', departmentName);
            localStorage.setItem('date', date);
            localStorage.setItem('subject', subject);
            localStorage.setItem('content', content);

            alert('Memo details saved in local storage!');
        });

        // Function to populate form with saved data
        function populateForm() {
            if (localStorage.getItem('username')) {
                document.getElementById('username').value = localStorage.getItem('username');
            }
            if (localStorage.getItem('departmentName')) {
                document.getElementById('departmentName').value = localStorage.getItem('departmentName');
            }
            if (localStorage.getItem('date')) {
                document.getElementById('date').value = localStorage.getItem('date');
            }
            if (localStorage.getItem('subject')) {
                document.getElementById('subject').value = localStorage.getItem('subject');
            }
            if (localStorage.getItem('content')) {
                document.getElementById('content').value = localStorage.getItem('content');
            }
        }

        // Populate the form with saved data on page load
        window.onload = populateForm;
    </script>
<script>
    function updateFileURL() {
        var fileInput = document.getElementById('fileUpload');
        var fileURLInput = document.getElementById('fileurl');
        
        if (fileInput.files.length > 0) {
            var file = fileInput.files[0];
            fileURLInput.value = file.name;
        } else {
            fileURLInput.value = '';
        }
    }
</script>
<?php include 'footer.php'; ?>

</body>

</html>



