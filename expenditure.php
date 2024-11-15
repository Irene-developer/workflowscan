<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="x-icon" href="KCBLLOGO.PNG">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Expenditure Imprest</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        body {
            font-family: "Open Sans", Arial, "Helvetica Neue", Helvetica, "Segoe UI", Roboto, "Droid Sans", "Fira Sans", "Lato", "Noto Sans", "PT Sans", "Ubuntu", Cantarell, "Gill Sans", "Lucida Grande", Tahoma, Verdana, "Geneva", "Trebuchet MS", "Century Gothic", "Franklin Gothic Medium", "Lucida Sans Unicode", "Arial Black", "Impact", sans-serif, "Courier New", Courier, "Lucida Console", Monaco, "Andale Mono", monospace, Georgia, "Times New Roman", Times, serif, "Palatino Linotype", "Book Antiqua", "MS Serif", "Comic Sans MS", "Comic Sans", cursive;
            margin: 0;
            padding: 0;
        }
        table {
            width: 99%;
            border-collapse: collapse;
            border: 1px solid #ddd;
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
        a.navigation-link {
            margin-left: 1200px;
            color: #3385ff;
            max-width: 20px;
            text-decoration: none;
            background-color: transparent;
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
        input[type="text"], input[type="date"], textarea, button, input[type="file"] {
            width: calc(100% - 10px);
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
        .add_memo_link {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    cursor: pointer; 
                    max-width: 99%;
                }

        .add_memo_link a {
                    width: 20px;
                    
                }
    </style>
</head>
<body>
            <div class='add_memo_link'>
                <p></p>
    <a href="fill_exp.php" onclick="addexpreq()" class="navigation-link">
        <li class="fa fa-plus-circle"></li>
    </a>
            </div>
    <table>
        <thead>
            <tr>
                <!--th>ID</th-->
                <!--th>Subject</th-->
                <th>Status</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Retire</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
<?php
// Start the session
//session_start();

// Include your database connection file
include 'include.php';

// Check if the username is set in the session
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Prepare the SQL statement
    $sql = "SELECT *
            FROM imprest_expenditure
            WHERE username = ?";

    $stmt = $conn->prepare($sql);
    
    // Use "s" for string data type instead of "i"
    $stmt->bind_param("s", $username);
    
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";

            $status = $row["status"];
            if (!empty($status)) {
                if ($status == 'pending') {
                    echo '<td><span style="color: blue;">Pending</span></td>';
                } elseif ($status == 'approved') {
                    echo '<td><span style="color: green; background-color: #e6ffe6; padding: 5px; border: 1px solid; border-color: green;">Approved</span>';
                } elseif ($status == 'declined') {
                    echo '<td><span style="color: red;">Declined</span></td>';
                } else {
                    echo '<td>' . htmlspecialchars($status) . '</td>';
                }
            } else {
                echo '<td><span style="color: blue;">Pending</span></td>';
            }

            echo "<td>" . $row["date"] . "</td>";
            echo "<td>" . $row["imprest_amount"] . "</td>";

            echo "<td style='cursor: pointer;' onclick='showPopupretire(" . json_encode($row) . ")'>";
            if (($row["retirement_status"] == "Pending Retirement") && ($row["status"] == "approved")) {
                echo "<i class='bx bx-refresh bx-spin' title='Pending' style='color: blue'></i>";
            } elseif (($row["status"] == "declined") || ($row["status"] == "pending")) {
                echo "<span onclick='event.stopPropagation();'><i class='fa fa-remove' title='Retired' style='color: red'></i></span>";
            } else {
                echo "<span onclick='event.stopPropagation();'><i class='fa fa-check' title='Retired' style='color: green'></i></span>";
            }
            echo "</td>";

            echo "<td style='cursor: pointer;' onclick=\"window.location.href = 'view_expenditure_memo.php?imprest_id=" . $row['imprest_id'] . "'\">";
            echo "<i class='fa fa-eye' data-toggle='tooltip' title='View' style='color: #3385ff;'></i>";
           /* if ($status == 'pending') {
                // code...
            echo "<i class='fa fa-trash' data-toggle='tooltip' title='delete' style='color: lightcoral;'></i>";
            echo "<i class='fa fa-edit' data-toggle='tooltip' title='edit' style='color: lightgreen;'></i>";
            }*/

            echo "</td>";

            echo "</tr>";
        }
    } else {
        echo "<tr style='color: red;  background-color: #ffe6e6; padding: 5px; border:1px solid; border-radius: 0.5em; border-color: red;'><td colspan='7' >No memos found</td></tr>";
    }
}

// Close the database connection
$conn->close();
?>

        </tbody>
    </table>

    <div id="popup" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closePopup()">&times;</span>
            <h2>Imprest Retirement Form</h2>
            <form id="retirement-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                <input type="hidden" id="applicant-name" name="applicant-name">
                <input type="hidden" id="designation" name="designation">
                <input type="hidden" id="department" name="department">
                <input type="hidden" id="claim-date" name="claim-date">
                <input type="hidden" id="imprest-reference-code" name="imprest-reference-code">
                
                <label for="claim-nature">Nature of Claim:</label>
                <textarea id="claim-nature" name="claim-nature" required></textarea>

                <label for="file-upload">Attach File(s):</label>
                <input type="file" id="file-upload" name="file-upload[]" multiple required ondragover="handleDragOver(event)" ondrop="handleFileDrop(event)">
                <div id="file-upload-drop-area" ondragover="handleDragOver(event)" ondrop="handleFileDrop(event)">
                    Drag & Drop files here
                </div>
              
                <label for="claimant-signature">Signature of Claimant:</label>
                  <div>
                    <?php
//session_start();
require 'include.php'; // Make sure this file contains your database connection code

$username = $_SESSION['username']; // Retrieve the username from the session

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
?>
        <?php if (!empty($signature_path)): ?>
            <img src="<?php echo htmlspecialchars($signature_path); ?>" alt="Signature" width='100' height='49'>
        <?php else: ?>
            <p>No signature found for the user.</p>
        <?php endif; ?>
    </div>
                <input type="text" id="claimant-signature" name="claimant-signature" required readonly value="<?php echo htmlspecialchars($signature_path); ?>">


                <button type="submit">Submit</button>
            </form>
        </div>
    </div>

    <script>
        function showPopupretire(row) {
            document.getElementById('applicant-name').value = row.username;
            document.getElementById('designation').value = row.Position_name;
            document.getElementById('department').value = row.department_name;
            document.getElementById('claim-date').value = row.date;
            document.getElementById('imprest-reference-code').value = row.imprest_id;

            document.getElementById('popup').style.display = 'block';
        }


/*for retire 
     function showPopup(rowData) {
            document.getElementById('applicant-name').value = rowData.first_name + ' ' + rowData.last_name;
            document.getElementById('designation').value = rowData.Position_name;
            document.getElementById('department').value = rowData.department_name;
            document.getElementById('claim-nature').value = '';
            document.getElementById('claimant-signature').value = '';
            document.getElementById('claim-date').value = new Date().toISOString().split('T')[0];
            document.getElementById('imprest-reference-code').value = rowData.imprest_id;

            document.getElementById('popup').style.display = 'block';
        }

    */

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
    event.dataTransfer.dropEffect = 'copy'; // Explicitly show this is a copy.
}

// Function to handle file drop event
function handleFileDrop(event) {
    event.preventDefault();
    event.stopPropagation();
    
    var files = event.dataTransfer.files;
    var dropArea = document.getElementById('file-upload-drop-area');
    
    // Display dropped files in the drop area
    dropArea.innerHTML = '';
    for (var i = 0; i < files.length; i++) {
        dropArea.innerHTML += files[i].name + '<br>';
    }
    
    // Update the file input element
    var fileInput = document.getElementById('file-upload');
    fileInput.files = files;
}

// Additional function to reset drop area (optional)
function resetDropArea() {
    var dropArea = document.getElementById('file-upload-drop-area');
    dropArea.innerHTML = 'Drag & Drop files here';
}
    </script>

<?php
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

            // Send email to the applicant
            $sessionUsername = $_SESSION['username']; // Get username from session

            // Fetch applicant's email
            $query_applicant_email = "SELECT email FROM employee_access WHERE username = ?";
            if ($stmt_email = $conn->prepare($query_applicant_email)) {
                $stmt_email->bind_param("s", $sessionUsername);
                $stmt_email->execute();
                $result = $stmt_email->get_result();
                
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $applicantEmail = $row['email'];

                    // Create PHPMailer instance
                    $mail = new PHPMailer(true);
                    try {
                        // Server settings  172.18.155.32


                        $mail->isSMTP();
                        $mail->Host       = '172.18.155.32';
                        $mail->SMTPAuth   = true;
                        $mail->Username   = 'notifications@kcblbank.co.tz';
                        $mail->Password   = 'Balancesheet@2025';
                        //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port       = 25;

                        // Recipients
                        $mail->setFrom('notifications@kcblbank.co.tz', 'WORK FLOW SYSTEM');
                        $mail->addAddress($applicantEmail);
                        $mail->addReplyTo('notifications@kcblbank.co.tz', 'Information');

                        // Email content
                        $mail->isHTML(true);
                        $mail->Subject = 'Retirement Imprest Request Submitted';
                        $mail->Body    = "Your imprest request has been submitted successfully.";

                        $mail->send();
                    } catch (Exception $e) {
                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }
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

                        // Create PHPMailer instance
                        $mail = new PHPMailer(true);
                        try {



                            // Server settings
                            $mail->isSMTP();
                            $mail->Host       = '172.18.155.32';
                            $mail->SMTPAuth   = true;
                            $mail->Username   = 'notifications@kcblbank.co.tz';
                            $mail->Password   = 'Balancesheet@2025';
                            //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                            $mail->Port       = 25;

                            // Recipients
                            $mail->setFrom('notifications@kcblbank.co.tz', 'WORK FLOW SYSTEM');
                            $mail->addAddress($approver1Email);
                            $mail->addReplyTo('notifications@kcblbank.co.tz', 'Information');

                            // Email content
                            $mail->isHTML(true);
                            $mail->Subject = 'Retirement Imprest Request';
                            $mail->Body    = "You have a retirement imprest request with ID $insertedId requiring your action.";

                            $mail->send();
                        } catch (Exception $e) {
                            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                        }
                    }

                    $stmt_email->close();
                }
            }

            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Records inserted successfully.'
                    }).then(function() {
                        window.location = 'dashboard.php'; // Redirect to a new page if needed
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

        // Close statement
        $stmt->close();
    }
}

// Close connection
$conn->close();
?>



</body>
</html>
