<?php
include 'include.php';
// Start the PHP session
//session_start();
include('session_timeout.php');
// Check if the username is set in the session
if(isset($_SESSION['username']) && isset($_SESSION['department_name']) && isset($_SESSION['Position_name'])) {
    // If username is set, retrieve and display it
    $username = $_SESSION['username'];
    $department_name=$_SESSION['department_name'];
    $Position_name = $_SESSION['Position_name'];
    
}
            use PHPMailer\PHPMailer\PHPMailer;
            use PHPMailer\PHPMailer\Exception;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="x-icon" href="KCBLLOGO.PNG">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REQUEST FOR EXPENDITURE IMPREST</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="assets/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/quill/quill.snow.css">
    <script src="assets/quill/quill.min.js"></script>
    <script src="assets/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="assets/js/jquery/jquery-3.7.1.min.js"></script>
    <style>
        h3 {
        color: #3385ff; /* Set heading color */
        font-size: 1.2em; /* Adjust heading font size */
        margin-bottom: 10px; /* Add some space below the heading */
    }
/* Add styles for dropdown options */
    .dropdown-option {
        padding: 8px;
        cursor: pointer;
    }

    .dropdown-option:hover {
        background-color: #f0f0f0;
    }
            /* Basic styling for the dropdown */
       /* Basic styling for the dropdown */
        #branchDropdown {
            display: none;
            position: absolute;
            background-color: white;
            border: 1px solid #ccc;
            z-index: 1000;
        }

        .dropdown-option {
            padding: 8px;
            cursor: pointer;
        }

        .dropdown-option:hover {
            background-color: #f0f0f0;
        }

         /* Basic styling for the dropdown */
        #branchDropdown {
            display: none;
            position: absolute;
            background-color: white;
            border: 1px solid #ccc;
            z-index: 1000;
        }

        .dropdown-option {
            padding: 8px;
            cursor: pointer;
        }

        .dropdown-option:hover {
            background-color: #f0f0f0;
        }

        /* Basic styling for the modal */
        #popupModal {
            display: none; /* Hidden by default */
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5); /* Black background with opacity */
            z-index: 1001; /* Make sure the modal is above other content */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
                /* Styling for the checkbox list */
        .checkbox-list {
            list-style-type: none;
            padding: 0;
        }

        .checkbox-list li {
            margin-bottom: 10px;
        }
        .hidden {
        display: none;
    }
    .hidden {
        display: none;
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


                #popupModalf {
            display: none; /* Hidden by default */
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5); /* Black background with opacity */
            z-index: 1001; /* Make sure the modal is above other content */
        }

        .modal-contentf {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .closef {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .closef:hover,
        .closef:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        /* Input Field Container */
.input-group {
    margin-bottom: 15px; /* Space below the input group */
    padding: 10px; /* Padding around the container */
    border: 1px solid #ddd; /* Light border around the container */
    border-radius: 4px; /* Slightly rounded corners */
    background-color: #f9f9f9; /* Light grey background */
}

/* Label Styling */
.input-group label {
    display: block; /* Makes label occupy its own line */
    margin-bottom: 8px; /* Space below the label */
    font-size: 16px; /* Font size for the label */
    font-weight: bold; /* Bold text for emphasis */
    color: #333; /* Dark grey color for text */
}

/* Input Field Styling */
.input-group input[type="text"] {
    width: 100%; /* Full width of the container */
    padding: 10px; /* Padding inside the input field */
    border: 1px solid #ccc; /* Light grey border */
    border-radius: 4px; /* Slightly rounded corners */
    box-sizing: border-box; /* Includes padding and border in the element's width and height */
    font-size: 16px; /* Font size for the input text */
    color: #333; /* Dark grey text color */
    background-color: #fff; /* White background */
    transition: border-color 0.3s ease, box-shadow 0.3s ease; /* Smooth transitions for focus state */
}

/* Input Field Focus State */
.input-group input[type="text"]:focus {
    border-color: #3385ff; /* Blue border color on focus */
    box-shadow: 0 0 8px rgba(51, 133, 255, 0.3); /* Subtle blue shadow on focus */
    outline: none; /* Removes default outline */
}
/* Styling for the dropdown list */
.dropdown-list {
    position: absolute;
    width: calc(100% - 85%); /* Adjust width to align with input field */
    max-height: 150px;
    overflow-y: auto;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: #fff;
    z-index: 1001;
}

/* Styling for dropdown options */
.dropdown-option {
    padding: 10px;
    cursor: pointer;
}

/* Change background color on hover for dropdown options */
.dropdown-option:hover {
    background-color: #f0f0f0;
}
.add-dropdown-btn {
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 20px;
    margin-top: 10px;
}
/* Remove Dropdown Button Styling */
.remove-dropdown-btn {
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 20px;
    margin-top: 10px;
}

.remove-dropdown-btn:hover {
    background-color: #c82333;
}
/* General styling for the modal set button */
#setButton {
    background-color: #4CAF50; /* Green background */
    color: white;              /* White text */
    padding: 10px 20px;        /* Padding */
    border: none;              /* Remove borders */
    cursor: pointer;           /* Pointer cursor on hover */
    font-size: 16px;           /* Font size */
    border-radius: 5px;        /* Rounded corners */
    margin-top: 10px;          /* Space above the button */
    display: inline-block;     /* Inline block for proper alignment */
    transition: background-color 0.3s; /* Smooth background color transition */
}

/* Add a darker background color on hover */
#setButton:hover {
    background-color: #45a049;
}
/* Set button alignment inside the modal */
.modal-footer {
    text-align: right;  /* Align to the right */
    padding-top: 20px;  /* Add space above */
}
    </style>
</head>
<body>
       <div class="form-container">
        <div class="back-button-container">
            <a href="dashboard.php" class="back-button">

                <img src="back-icon.png" alt="Back Icon">
                
            </a>
        </div>
<form action="" method="post" enctype="multipart/form-data">
    <div class="all_head_section">
        
   <div class="container-headsection">
    <div class="container-textheadsection">
        <h1>CO-OPERATIVE BANK OF TANZANIA</h1>
        <h4>REQUEST FOR EXPENDITURE IMPREST</h4>  
    </div>
    <div class="container-image">
        <img src="KCBLLOGO.png">
    </div>
</div>

    </div>

  

    <!-- Table Section -->
    <table class="table-container">
        <tr>
            <th>Name of Applicant</th>
            <th>Designation</th>
            <th>Department</th>
            <th>Amount Requested</th>
            <th>Switch Branch:
  <!-- Add a select element for choosing between 000 or 001 -->
               
            </th>
        </tr>
        <tr>
            <td><input type="text" name="applicant_name" placeholder="Enter your name" value="<?php echo $username; ?>" required readonly></td>
            <td><input type="text" name="designation" placeholder="Enter your designation" value="<?php echo $Position_name; ?>" required readonly></td>
            <td><input type="text" name="department" placeholder="Enter your department" value="<?php echo $department_name ?>" required readonly></td>
            <td><input type="number" name="requested_amount" placeholder="Enter the amount in Tshs" required></td>
                <td>
        <input type="text" name="switched_branch" id="switchedBranch" placeholder="Choose branch" required readonly>
        <div id="branchDropdown">
            <div class="dropdown-option" data-value="000">HQ</div>
            <div class="dropdown-option" data-value="001">BRANCH</div>
        </div>
    </td>

    <div id="branchManagerSection" style="display: none;">
        <!-- Content for branchManagerSection -->
    </div>

    <div id="FinanceSection" style="display: none;">
        <!-- Content for financeManagerSection -->
    </div>


        </tr>
    </table>
    <h3>Purpose of Expenditure Imprest</h3>
<textarea id="editor" name="purpose" rows="10" cols="80" placeholder="Please provide purpose of expenditure imprest">
    <?php echo isset($memo['content']) ? htmlspecialchars_decode($memo['content']) : ''; ?>
</textarea>

<div class="container-signdate">
    <div>
        <h3 id="signature-trigger">Signature <i class="fas fa-signature" style="color: green;"></i></h3>
        <div id="signature-image-container"></div>
        <div id="signature-url"></div> <!-- Added this div to display the signature URL -->
</div>

    
<div>
    <h3>Date and Time</h3>
    <input type="datetime-local" id="date" name="date" required readonly>
</div>
  <!-- Modal Popup fro -->
    <div id="popupModal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Branch Manager Details</h2>
            <ul id="popupContent" class="checkbox-list"></ul>
        </div>
    </div>



    <div id="branch-manager-name-section" class="hidden">
        <h3 class="hidden">Branch Manager</h3>
        <input type="text" id="branch_manager_name" name="branch_manager_name" class="hidden">
</div>



   <!-- Modal Popup fro -->
    <div id="popupModalf">
        <div class="modal-contentf">
            <span class="closef">&times;</span>
            <h2>Finance Manager Details</h2>
            <ul id="popupContentf" class="checkbox-list"></ul>
        </div>
    </div>



<div id="finance-manager-name-section" class="hidden">
        <h3 class="hidden">Finance Manager</h3>
        <input type="text" id="finance_manager_name" name="finance_manager_name" class="hidden">
</div>

<div id="bind-memo-section" >
        <h3>Bind Memo</h3>
        <input type="text" id="bind_memo" name="bind_memo">
    </div>
    
</div>

</div>

  <p class="outstanding-imprest">
    <?php
// Include the database connection file
include 'include.php';
// session_start(); // Start the session if not already started

// Retrieve the username from the session
$username = $_SESSION['username'];

// Calculate the outstanding imprest amount
$sqlamount = "SELECT SUM(outstanding_imprest_amount) as total_outstanding FROM imprest_expenditure WHERE username = ?";
$stmt = $conn->prepare($sqlamount);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$outstanding_imprest_amount = 0;

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $outstanding_imprest_amount = $row['total_outstanding'];
}
$stmt->close();
?>
Your outstanding Imprest amounting is Tshs: 
<input id="imprestAmount" style="color: red;" value="<?php echo htmlspecialchars($outstanding_imprest_amount); ?>" readonly>
</p>

<!-- Submit Button -->
<button type="submit">Submit</button>
</form>
<div id="popup" class="popup">
    <div class="popup-content">
        <span class="close-button">&times;</span>
        <h2>Bind Memo</h2>
        <form id="memo-form">
            <div id="memo-list">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Subject</th>
                            <th>Select</th>
                        </tr>
                    </thead>
                    <tbody id="memo-table-body">
                        <!-- Rows will be inserted here by JavaScript -->
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>

<?php
// Start the PHP session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
// Retrieve form data from session and POST
    $username = $_SESSION['username'];
    $department_name = $_SESSION['department_name'];
    $Position_name = $_SESSION['Position_name'];
    $imprest_amount = $_POST['requested_amount'];
    $branch_name = $_POST['switched_branch'];
    $imprest_purpose = $_POST['purpose'];
    $date = $_POST['date'];
    $signature_path = $_POST['signature_url'];
    $bind_memo = isset($_POST['bind_memo']) ? $_POST['bind_memo'] : null;
    
    
    // Get approvers from session
    $approver1 = isset($_SESSION['approver1']) ? $_SESSION['approver1'] : null;
    $approver2 = isset($_SESSION['approver2']) ? $_SESSION['approver2'] : null;
    $approver3 = isset($_SESSION['approver3']) ? $_SESSION['approver3'] : null;

       // Calculate the outstanding imprest amount
    include 'include.php';
    $sqlamount = "SELECT SUM(outstanding_imprest_amount) as total_outstanding FROM imprest_expenditure WHERE username = ?";
    $stmt = $conn->prepare($sqlamount);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $outstanding_imprest_amount = 0;

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $outstanding_imprest_amount = $row['total_outstanding'];
    }
    $stmt->close();

    if ($outstanding_imprest_amount > 0) {
        // Display alert if outstanding imprest amount is greater than 0
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Expenditure Imprest Request Not Allowed',
                text: 'You have Tshs: $outstanding_imprest_amount as outstanding imprest amount.',
                showConfirmButton: true,
                timer: 15000
            }).then(function () {
                window.location.href = 'dashboard.php';
            });
        </script>";
    } else {
  // Insert data into the database
        $sql = "INSERT INTO imprest_expenditure (username, Position_name, bind_memo, department_name, imprest_amount, branch_name, signature_path, imprest_purpose, date, outstanding_imprest_amount, approver1, approver2, approver3) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssisss", $username, $Position_name, $bind_memo, $department_name, $imprest_amount, $branch_name, $signature_path, $imprest_purpose, $date, $outstanding_imprest_amount, $approver1, $approver2, $approver3);

        // Check if the insertion was successful
        if ($stmt->execute()) {
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

            // Create PHPMailer instance
require 'E:/xampp/htdocs/SERVICENET/PHPMailer-master/src/Exception.php';
require 'E:/xampp/htdocs/SERVICENET/PHPMailer-master/src/PHPMailer.php';
require 'E:/xampp/htdocs/SERVICENET/PHPMailer-master/src/SMTP.php';



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

                // Build imprest review link
                $imprestReviewLink = $baseURL . '?imprest_id=' . $conn->insert_id;

                // Recipients
                if (!empty($creatorEmail)) {
                    $mail->setFrom('notifications@kcblbank.co.tz', 'WORK FLOW SYSTEM');
                    $mail->addAddress($creatorEmail);                     // Add the creator email
                    $mail->addReplyTo('notifications@kcblbank.co.tz', 'Information');
                    
                    // Email content
                    $mail->isHTML(true);
                    $mail->Subject = 'New Imprest Request Created';
                    $mail->Body    = "A new imprest request has been created.<br><br>"
                                    . "Amount: $imprest_amount<br>"
                                    . "Purpose: $imprest_purpose<br>"
                                    . "Date: $date<br>"
                                    . "Please review the request at your earliest convenience by clicking the link below:<br>"
                                    . "<a href=\"$imprestReviewLink\">Review Imprest Request</a>";
                    $mail->send();
                }

                // Send email to approver (approver1)
                if (!empty($approver1)) {
                    $emailQuery = "SELECT email FROM employee_access WHERE username = ?";
                    $emailStmt = $conn->prepare($emailQuery);
                    $emailStmt->bind_param("s", $approver1);
                    $emailStmt->execute();
                    $emailResult = $emailStmt->get_result();

                    if ($emailResult->num_rows > 0) {
                        $row = $emailResult->fetch_assoc();
                        $approverEmail = $row['email'];

                        // Send email using PHPMailer
                        $mail->clearAddresses(); // Clear previous addresses
                        $mail->addAddress($approverEmail); // Add new recipient

                        // Email content
                        $mail->isHTML(true); // Ensure email is in HTML format
                        $mail->Subject = 'Imprest Request Requires Your Action';
                        $mail->Body    = "A new imprest request requires your action.<br><br>"
                                        . "From: $username<br>"
                                        . "Purpose: $imprest_safari_purpose<br>"
                                        . "Date: $date<br>"
                                        . "Please review the request and take the necessary action by clicking the link below:<br>"
                                        . "<a href=\"$imprestReviewLink\">Review Imprest Request</a>";

                        $mail->send();
                    } else {
                        echo "No email found for approver username: $approver1<br>";
                    }
                    $emailStmt->close();
                }

                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Safari Imprest Requested Successfully',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function () {
                        window.location.href = 'dashboard.php';
                    });
                </script>";
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }

    $stmt->close();
    $conn->close();
}
?>



    <script>
document.getElementById('bind-memo-section').addEventListener('click', function() {
    document.getElementById('popup').style.display = 'flex';

    fetch('fetch_memos.php')
        .then(response => response.json())
        .then(data => {
            const memoTableBody = document.getElementById('memo-table-body');
            memoTableBody.innerHTML = ''; // Clear existing rows
            data.forEach(memo => {
                const row = document.createElement('tr');
                const cellId = document.createElement('td');
                const cellSubject = document.createElement('td');
                const cellSelect = document.createElement('td');
                const selectButton = document.createElement('button');

                cellId.textContent = memo.id;
                cellSubject.textContent = memo.subject;

                selectButton.textContent = 'Select';
                selectButton.className = 'select-button';
                selectButton.addEventListener('click', function() {
                    selectMemo(memo.id);
                });

                cellSelect.appendChild(selectButton);

                row.appendChild(cellId);
                row.appendChild(cellSubject);
                row.appendChild(cellSelect);
                memoTableBody.appendChild(row);
            });
        })
        .catch(error => console.error('Error fetching memos:', error));
});

document.querySelector('.close-button').addEventListener('click', function() {
    document.getElementById('popup').style.display = 'none';
});

window.addEventListener('click', function(event) {
    if (event.target === document.getElementById('popup')) {
        document.getElementById('popup').style.display = 'none';
    }
});

function selectMemo(id) {
    document.getElementById('bind_memo').value = id;
    document.getElementById('popup').style.display = 'none';
}

document.getElementById('memo-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission
    const formData = new FormData(this);

    fetch('fill_exp.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Form submitted successfully!');
            // Optionally clear the form or perform other actions
        } else {
            alert('Error submitting form.');
        }
    })
    .catch(error => console.error('Error submitting form:', error));
});

</script>
<script>
    // Get the input element
    var inputElement = document.getElementById('imprestAmount');

    // Simulate fetching the amount (replace this with actual logic)
    var amount = ; // Example amount
    
    // Set the value of the input element
    inputElement.value = amount + " Tshs";
</script> 
<script>
function showInputFieldPopup1() {
    const contentf = `
        <div class="input-group">
            <label for="inputField_1">Enter details:</label>
            <br>
            <input type="text" name="inputField" id="inputField_1" placeholder="Choose Your Finance Officer">
            <br>
            <div id="dropdownList_1" class="dropdown-list" style="display: none; text-align: left;"></div>
            <button id="addDropdown" class="add-dropdown-btn">+</button>
            <div id="additionalDropdowns"></div>
            <button id="setButton" class="set-button">Set</button>
        </div>`;

    popupContentf.innerHTML = contentf;
    popupModalf.style.display = 'block';

    // Fetch the dropdown options for the initial input field
    fetchDropdownOptions1('inputField_1', 'dropdownList_1');

    // Event listeners for initial input field and dropdown button
    setupDropdownEventListeners('inputField_1', 'dropdownList_1');

    document.getElementById('addDropdown').addEventListener('click', function() {
        addNewDropdown1();
    });

    document.getElementById('setButton').addEventListener('click', function() {
        storeDataInSession();
    });
}

function setupDropdownEventListeners(inputId, dropdownId) {
    const inputField = document.getElementById(inputId);
    const dropdownList = document.getElementById(dropdownId);

    inputField.addEventListener('click', function(event) {
        event.stopPropagation();
        dropdownList.style.display = 'block';
    });

    document.addEventListener('click', function(event) {
        if (!dropdownList.contains(event.target) && event.target !== inputField) {
            dropdownList.style.display = 'none';
        }
    });
}

function addNewDropdown1() {
    const additionalDropdowns = document.getElementById('additionalDropdowns');
    const index = additionalDropdowns.children.length + 2;

    const newDropdown = document.createElement('div');
    newDropdown.className = 'dropdown-container';
    newDropdown.innerHTML = `
        <input type="text" id="inputField_${index}" class="new-input" placeholder="Choose Your Finance Officer">
        <button class="remove-dropdown-btn">-</button>
        <div id="dropdownList_${index}" class="dropdown-list" style="display: none;"></div>`;
    
    additionalDropdowns.appendChild(newDropdown);

    // Setup event listeners for the new dropdown
    setupDropdownEventListeners(`inputField_${index}`, `dropdownList_${index}`);

    // Add event listener to remove the dropdown
    newDropdown.querySelector('.remove-dropdown-btn').addEventListener('click', function() {
        additionalDropdowns.removeChild(newDropdown);
    });

    // Fetch the dropdown options for the new dropdown
    fetchDropdownOptions1(`inputField_${index}`, `dropdownList_${index}`);
}

function fetchDropdownOptions1(inputId, dropdownId) {
    const dropdownList = document.getElementById(dropdownId);
    const inputField = document.getElementById(inputId);

    fetch('fetchBranchFinanceDetails.php')
        .then(response => response.json())
        .then(data => {
            dropdownList.innerHTML = '';
            data.forEach(item => {
                const optionDiv = document.createElement('div');
                optionDiv.className = 'dropdown-option';
                optionDiv.innerHTML = `${item.Position_name}: - ${item.username}`;
                optionDiv.dataset.value = item.username;  // Store the username in a data attribute
                dropdownList.appendChild(optionDiv);
            });

            // Add event listeners to dropdown options
            dropdownList.querySelectorAll('.dropdown-option').forEach(option => {
                option.addEventListener('click', function() {
                    inputField.value = this.dataset.value;  // Set input field to username only
                    dropdownList.style.display = 'none';
                });
            });
        })
        .catch(() => {
            console.error('Failed to fetch dropdown options.');
        });
}


function storeDataInSession() {
    const inputValues = Array.from(popupModalf.querySelectorAll('input[type="text"]'))
        .map(input => input.value)
        .filter(value => value);

    fetch('storeSessionData.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ inputValues })
    })
    .then(response => {
        if (response.ok) {
            alert('Data stored in session successfully!');
            popupModalf.style.display = 'none';
        } else {
            alert('Failed to store data in session.');
        }
    })
    .catch(() => {
        alert('Failed to store data in session.');
    });
}

// Existing code for handling dropdown and modal behavior
document.getElementById('switchedBranch').addEventListener('click', function(event) {
    event.stopPropagation();
    document.getElementById('branchDropdown').style.display = 'block';
});

document.querySelectorAll('.dropdown-option').forEach(option => {
    option.addEventListener('click', function() {
        document.getElementById('switchedBranch').value = this.innerText;

        if (this.dataset.value === '000') {
            showInputFieldPopup1();
        }

        document.getElementById('branchDropdown').style.display = 'none';
    });
});

document.querySelector('.closef').addEventListener('click', function() {
    popupModalf.style.display = 'none';
});

window.addEventListener('click', function(event) {
    if (event.target === popupModalf) {
        popupModalf.style.display = 'none';
    }
});
</script>


<script>
function showInputFieldPopup() {
    const contentf = `
        <div class="input-group">
            <label for="inputField_1">Enter details:</label>
            <br>
            <input type="text" name="inputField" id="inputField_1" placeholder="Choose Your Finance Officer">
            <br>
            <div id="dropdownList_1" class="dropdown-list" style="display: none; text-align: left;"></div>
            <button id="addDropdown" class="add-dropdown-btn">+</button>
            <div id="additionalDropdowns"></div>
            <button id="setButton" class="set-button">Set</button>
        </div>`;

    popupContentf.innerHTML = contentf;
    popupModalf.style.display = 'block';

    // Fetch the dropdown options for the initial input field
    fetchDropdownOptions('inputField_1', 'dropdownList_1');

    // Event listeners for initial input field and dropdown button
    setupDropdownEventListeners('inputField_1', 'dropdownList_1');

    document.getElementById('addDropdown').addEventListener('click', function() {
        addNewDropdown();
    });

    document.getElementById('setButton').addEventListener('click', function() {
        storeDataInSession();
    });
}

function setupDropdownEventListeners(inputId, dropdownId) {
    const inputField = document.getElementById(inputId);
    const dropdownList = document.getElementById(dropdownId);

    inputField.addEventListener('click', function(event) {
        event.stopPropagation();
        dropdownList.style.display = 'block';
    });

    document.addEventListener('click', function(event) {
        if (!dropdownList.contains(event.target) && event.target !== inputField) {
            dropdownList.style.display = 'none';
        }
    });
}

function addNewDropdown() {
    const additionalDropdowns = document.getElementById('additionalDropdowns');
    const index = additionalDropdowns.children.length + 2;

    const newDropdown = document.createElement('div');
    newDropdown.className = 'dropdown-container';
    newDropdown.innerHTML = `
        <input type="text" id="inputField_${index}" class="new-input" placeholder="Choose Your Finance Officer">
        <button class="remove-dropdown-btn">-</button>
        <div id="dropdownList_${index}" class="dropdown-list" style="display: none;"></div>`;
    
    additionalDropdowns.appendChild(newDropdown);

    // Setup event listeners for the new dropdown
    setupDropdownEventListeners(`inputField_${index}`, `dropdownList_${index}`);

    // Add event listener to remove the dropdown
    newDropdown.querySelector('.remove-dropdown-btn').addEventListener('click', function() {
        additionalDropdowns.removeChild(newDropdown);
    });

    // Fetch the dropdown options for the new dropdown
    fetchDropdownOptions(`inputField_${index}`, `dropdownList_${index}`);
}

function fetchDropdownOptions(inputId, dropdownId) {
    const dropdownList = document.getElementById(dropdownId);
    const inputField = document.getElementById(inputId);

    fetch('fetchBranchManagerDetails.php')
        .then(response => response.json())
        .then(data => {
            dropdownList.innerHTML = '';
            data.forEach(item => {
                const optionDiv = document.createElement('div');
                optionDiv.className = 'dropdown-option';
                optionDiv.innerHTML = `${item.Position_name}: - ${item.username}`;
                optionDiv.dataset.value = item.username;  // Store the username in a data attribute
                dropdownList.appendChild(optionDiv);
            });

            // Add event listeners to dropdown options
            dropdownList.querySelectorAll('.dropdown-option').forEach(option => {
                option.addEventListener('click', function() {
                    inputField.value = this.dataset.value;  // Set input field to username only
                    dropdownList.style.display = 'none';
                });
            });
        })
        .catch(() => {
            console.error('Failed to fetch dropdown options.');
        });
}


function storeDataInSession() {
    const inputValues = Array.from(popupModalf.querySelectorAll('input[type="text"]'))
        .map(input => input.value)
        .filter(value => value);

    fetch('storeSessionData.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ inputValues })
    })
    .then(response => {
        if (response.ok) {
            alert('Data stored in session successfully!');
            popupModalf.style.display = 'none';
        } else {
            alert('Failed to store data in session.');
        }
    })
    .catch(() => {
        alert('Failed to store data in session.');
    });
}

// Existing code for handling dropdown and modal behavior
document.getElementById('switchedBranch').addEventListener('click', function(event) {
    event.stopPropagation();
    document.getElementById('branchDropdown').style.display = 'block';
});

document.querySelectorAll('.dropdown-option').forEach(option => {
    option.addEventListener('click', function() {
        document.getElementById('switchedBranch').value = this.innerText;

        if (this.dataset.value === '001') {
            showInputFieldPopup();
        }

        document.getElementById('branchDropdown').style.display = 'none';
    });
});

document.querySelector('.closef').addEventListener('click', function() {
    popupModalf.style.display = 'none';
});

window.addEventListener('click', function(event) {
    if (event.target === popupModalf) {
        popupModalf.style.display = 'none';
    }
});
</script>
 <!-- JavaScript to handle fetching signature -->
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
    // Function to get the current date and time in the format YYYY-MM-DDTHH:MM
    function getCurrentDateTime() {
        var today = new Date();
        var year = today.getFullYear();
        var month = String(today.getMonth() + 1).padStart(2, '0');
        var day = String(today.getDate()).padStart(2, '0');
        var hours = String(today.getHours()).padStart(2, '0');
        var minutes = String(today.getMinutes()).padStart(2, '0');
        
        // Format: YYYY-MM-DDTHH:MM (T is the separator required by datetime-local)
        return year + '-' + month + '-' + day + 'T' + hours + ':' + minutes;
    }

    // Set the value of the datetime input to the current date and time
    document.getElementById('date').value = getCurrentDateTime();
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
        font_formats: 'Arial=arial,helvetica,sans-serif;Courier New=courier new,courier;' +
                      'Georgia=georgia,palatino;Helvetica=helvetica;Times New Roman=times new roman,times;' +
                      'Trebuchet=trebuchet ms,geneva;Verdana=verdana,geneva;Century Gothic=Century Gothic;Calibri=calibri;',
        fontsize_formats: '10px 12px 14px 16px 18px 24px 36px', // Available font sizes
        height: 300, // Set editor height
        setup: function (editor) {
            editor.on('change', function () {
                editor.save(); // Save content to the textarea on change
            });
        }
    });
</script>
</body>
</html>

 
