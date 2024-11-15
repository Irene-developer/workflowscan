<?php 
//session_start();
//echo "<p>" . $_SESSION['employee_type'] . "</p>";
include('session_timeout.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="x-icon" href="KCBLLOGO.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CBT REQUESTS Dashboard</title>
    <link rel="stylesheet" type="text/css" href="dashboard.css">
    <link rel="stylesheet" href="assets/fas fas fas 3/css/font-awesome.min.css">
    <link href="assets/css/responsive.css" rel="stylesheet" type="text/css"/>     
    <link rel="stylesheet" href="assets/sweetalert2/sweetalert2.min.css">
    <script src="assets/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="assets/js/jquery/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style type="text/css">
body {
              font-family: "Open Sans", Arial, "Helvetica Neue", Helvetica, "Segoe UI", Roboto, "Droid Sans", "Fira Sans", "Lato", "Noto Sans", "PT Sans", "Ubuntu", Cantarell, "Gill Sans", "Lucida Grande", Tahoma, Verdana, "Geneva", "Trebuchet MS", "Century Gothic", "Franklin Gothic Medium", "Lucida Sans Unicode", "Arial Black", "Impact", sans-serif, "Courier New", Courier, "Lucida Console", Monaco, "Andale Mono", monospace, Georgia, "Times New Roman", Times, serif, "Palatino Linotype", "Book Antiqua", "MS Serif", "Comic Sans MS", "Comic Sans", cursive;
            background: #fff;
            color: black;
            font-weight: 300;
            margin: 0; /* Remove default margin */
            padding: 0; /* Remove default padding */
        }
    .grid-container {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
  padding: 13px;
  margin-left: 70px;
}

.grid-item {
  background-color: white;
  border: 1px solid #ddd;
  border-radius: 5px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  padding: 20px;
}

.alerts {
  grid-column: span 2;
}

.grid-item h2 {
  background-color: #3385ff;
  color: white;
  margin: -20px -20px 10px -20px;
  padding: 10px;
  border-radius: 5px 5px 0 0;
}

.grid-item p {
  margin: 20px 0;
  color: #555;
}
       
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .closec {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .closec:hover,
        .closec:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            padding-top: 100px; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgba(0,0,0,0.5); 
        }

        .modal-content {
            background-color: #fff;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            animation: animatetop 0.4s;
        }

        @keyframes animatetop {
            from {top: -300px; opacity: 0} 
            to {top: 0; opacity: 1}
        }

        .closec {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .closec:hover,
        .closec:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .container {
            padding: 16px;
        }

        h2 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .pagination {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .pagination a {
            margin: 0 5px;
            padding: 8px 16px;
            text-decoration: none;
            background-color: #3385ff;
            color: white;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .pagination a:hover {
            background-color: #2a6bbf;
        }

        .pagination a.active {
            background-color: #0056b3;
        }
</style>
<script>
        $(document).ready(function() {
            // Function to fetch notifications from the server
            function fetchNotifications() {
                // Make an AJAX request to retrieve notifications
                $.ajax({
                    url: 'send_notification.php',
                    method: 'GET', // Change to 'POST' if necessary
                    success: function(response) {
                        // Append received notifications to the notification container
                        $('#notificationContainer').append(response);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching notifications:', error);
                    }
                });
            }
            
            // Fetch notifications initially when the page loads
            fetchNotifications();
            
            // Fetch notifications periodically (e.g., every 5 seconds)
            setInterval(fetchNotifications, 5000); // Adjust the interval as needed
        });
    </script>

</head>
<body>
	

<header class="Dashboard-header">
    <h3 class="logo-container" style="margin-bottom: 3px;"><img src="KCBLLOGO.png" class="logo"></img>CBT USER REQUEST Dashboard</h3>
     <div class="dropdown">
        <i class="fa fa-user fa-lg" style="margin-right: 40px;"></i>
        <!-- Dropdown content -->
        <div class="dropdown-content" style="text-align: center;">
            <!--a href="#" class="manage_account_link">Manage Account</a-->

            <a href="logout.php">Sign Out</a>

           
            <div id="notificationContainer"><li class="fa fa-bell" style="color: black;"></li></div>
            
        
        </div>
    </div>
</header>

<div class="all-container">
<div class="nav-bar">
<div class="scrollbar" id="style-1">

    <nav>
   <a href="#" onclick="loadhomeDetails('report_view.php')">
            <i class="fa fa-home fa-lg"></i>
            <span class="nav-text">Home</span>
   </a>

    <div class="dropdown">
            <a href="#" onclick="showdropimprest()">
                <i class="fa fa-paper-plane"></i>
                <span class="nav-text">Internal Memo</span>
            </a>
            <!-- Dropdown content -->
            <div class="dropdown-content" id="imprestDropdownContent">
                <a href="#" onclick="loadmemoDetails('Memo_request.php')" style="max-width: 60px">Memo Request</a>
<?php if(($_SESSION['employee_type'] == 1) || ($_SESSION['employee_type'] == 3) || ($_SESSION['employee_type'] == 4)): ?>
                <a href="#" onclick="loadmemoaprdetails('approve_memo.php')" style="max-width: 60px">Pending Memo Request</a>
<?php endif; ?>
                <a href="Approved_memos_list.php" style="max-width: 60px">Approved Memo Request</a>
            </div>
        </div>

        <div class="dropdown">
            <a href="#" onclick="showdropimprest()">
                <i class="fa fa-money"></i>
                <span class="nav-text">Imprest</span>
            </a>
            <!-- Dropdown content -->
            <div class="dropdown-content" id="imprestDropdownContent">
                <a href="#" onclick="loadexpDetails('expenditure.php')" style="max-width: 60px">Expenditure</a>

                <?php if(($_SESSION['employee_type'] == 1) || ($_SESSION['employee_type'] == 3) || ($_SESSION['employee_type'] == 4)): ?>
                <a href="#" onclick="loadexpDetails('approve_exp.php')"  style="max-width: 60px" >Approve Exp request</a>
                <?php endif; ?>

                <a href="#" onclick="loadsafDetails('safari.php')" style="max-width: 60px" >Safari</a>

                <?php if(($_SESSION['employee_type'] == 1) || ($_SESSION['employee_type'] == 3) || ($_SESSION['employee_type'] == 4)): ?>
                <a href="#"  onclick="loadsafDetails('safari_approve_imprest.php')"  style="max-width: 60px">Approve Safari request</a>
                <?php endif; ?>

                <?php if(($_SESSION['employee_type'] == 1) || ($_SESSION['employee_type'] == 3) || ($_SESSION['employee_type'] == 4)): ?>
                <a href="#" onclick="loadexpDetails('approve_retire.php')"  style="max-width: 60px" >Approve Imprest Retirement</a>
                <?php endif; ?>
            </div>
        </div>
<div class="dropdown">
    <a href="system_access_form_system.php">
        <i class="fa fa-envelope-o fa-lg"></i>
        <span class="nav-text">Access Request</span>
    </a>
    <div class="dropdown-content" id="imprestDropdownContent">
        <?php if (($_SESSION['employee_type'] == 3) || ($_SESSION['employee_type'] == 1)): ?>
            <a href="access_request_application.php">
                <span class="nav-text">System Access Application</span>
            </a>
        <?php endif; ?>
        <?php if(($_SESSION['employee_type'] == 3)): ?> 
            <a href="assigned_access_request_application.php">
                <span class="nav-text">Assigned System Access Application</span>
            </a>
            <a href="manage_system_access_report.php">
                <span class="nav-text">System Access Request Report</span>
            </a>
        <?php endif; ?>
    </div>
</div>

   <div class="dropdown">
 <a href="comming.php">
            <i class="fa fa-code-fork"></i>
            <span class="nav-text">Intranet_services</span>
   </a>
   
   </div>
   <div class="dropdown">
  <a href="#" onclick="loadmREQASSETDetails('request_asset.php')">
    <i class="fa fa-cubes"></i>
    <span class="nav-text">Asset Request</span>
  </a>
<div class="dropdown-content" id="imprestDropdownContent">
 <?php if(($_SESSION['employee_type'] == 3)): ?> 
<a href="#" onclick="loadmASSETDetails('asset_request.php')">
    <span class="nav-text">Asset Request</span>
  </a>
<a href="#" onclick="loadmASSETDetails('available_asset.php')">
    <span class="nav-text">Available Asset</span>
  </a>
  <?php endif; ?>
</div>

  </div>
    <div class="dropdown">
    <a href="log_ticket.php">
   <i class="fa fa-ticket"></i>
    <span class="nav-text">My Tickets</span>
  </a>
  <div class="dropdown-content" id="imprestDropdownContent">
 <?php if(($_SESSION['employee_type'] == 3)): ?> 
<a href="logged_services_request.php">
    Logged Tickets
  </a>
   <?php endif; ?>
  <?php if(($_SESSION['employee_type'] == 3)): ?> 
  <a href="logged_assigned_ticket_request.php">
    Assigned Tickets
  </a>
   <?php endif; ?>
    <?php if(($_SESSION['employee_type'] == 3)): ?> 
  <a href="logged_ticket_request_report.php">
    Ticket Reports
  </a>
   <?php endif; ?>
</div>
</div>
  <a href="comming.php">
   <i class="fa fa-gears"></i>
    <span class="nav-text">Setting</span>
  </a>
  <a href="comming.php">
    <i class="fa fa-question-circle fa-lg"></i>
    <span class="nav-text">Help</span>
  </a>
      <div class="dropdown">
                    <a href="#" onclick="showdropimprest()">
                <i class="fa fa-sticky-note"></i>
                <span class="nav-text">Reports</span>
            </a>

            <!-- Dropdown content -->
            <div class="dropdown-content" id="imprestDropdownContent">
<?php if(($_SESSION['employee_type'] == 1) || ($_SESSION['employee_type'] == 3) || ($_SESSION['employee_type'] == 4)): ?>
<a href="all_report.php" style="max-width: 60px">Memo</a>
<?php endif; ?>
                <a href="all_reports_exp.php" style="max-width: 60px">Expenditure</a>
<?php if(($_SESSION['employee_type'] == 1) || ($_SESSION['employee_type'] == 3) || ($_SESSION['employee_type'] == 4)): ?>
                <a href="all_reports_safari.php"  style="max-width: 60px">Safari</a>
<?php endif; ?>
                <a href="all_reports_retire.php" style="max-width: 60px">Retirement</a>
            </div>
        </div>

  <?php if(($_SESSION['employee_type'] == 3)): ?>
    
<a href="register_employees.php" onclick="loaduserDetailsx('register_employees.php')">
            <i class="fa fa-user-plus fa-lg"></i>
<span class="nav-text">Add Users</span>
            </a>
    
<?php endif; ?>

<?php if(($_SESSION['employee_type'] == 3)): ?>
<a href="department.php">
            <i class="fa fa-institution fa-lg"></i>
            <span class="nav-text">Department</span>
   </a>
<?php endif; ?>

<?php if(($_SESSION['employee_type'] == 3)): ?>
<a href="position.php">
            <i class="fa fa-group fa-lg"></i>
            <span class="nav-text">Position</span>
   </a>
<?php endif; ?>

<?php if(($_SESSION['employee_type'] == 1) || ($_SESSION['employee_type'] == 3) || ($_SESSION['employee_type'] == 4)): ?>
                <a href="comming.php" onclick="loadincidencedetails('retrieve_incidence.php')">
                    <i class="fa fa-warning"></i>
            <span class="nav-text">Incidence</span>
                </a>
<?php endif; ?>
<?php if(($_SESSION['employee_type'] == 1) || ($_SESSION['employee_type'] == 3) || ($_SESSION['employee_type'] == 4)): ?>
                <a href="user_logs.php">
                    <i class="fa fa-history"></i>
            <span class="nav-text">Logs Review</span>
                </a>
<?php endif; ?>
    </nav>
    </div>
</div>
<div class="container-body">
	<?php
// Check if the user is logged in
if (isset($_SESSION['username']) && isset($_SESSION['department_name']) && isset($_SESSION['Position_name']) && isset($_SESSION['employee_type'])) {
    // Display the logged-in user's name, department name, and position name

echo "<div class='container-body'>";
echo "<p>Welcome " . $_SESSION['username'] . "</p>";
echo "<p>" . $_SESSION['department_name'] . "</p>";
echo "<p>" . $_SESSION['Position_name'] . "</p>";
echo "</div>";




    // Get the username from the session
    $username = $_SESSION['username'];
    // Include database connection
    include 'include.php';

    // Query the database for the user's has_uploaded_signature
    $sql = "SELECT has_uploaded_signature FROM signature WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Default value for has_uploaded_signature
    $has_uploaded_signature = 0;

    if ($result->num_rows > 0) {
        // If user exists
        $row = $result->fetch_assoc();
        $has_uploaded_signature = $row["has_uploaded_signature"];
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // JavaScript for modal
    echo "<script>
            window.onload = function() {
                var modal = document.getElementById('myModal');
                var span = document.getElementsByClassName('close')[0];
                
                // Check if the user has not uploaded a signature (has_uploaded_signature is 0)
                if ($has_uploaded_signature === 0) {
                    // Display the modal
                    modal.style.display = 'block';
                }
                
                // Close the modal when user clicks on the close button
                span.onclick = function() {
                    modal.style.display = 'none';
                }
                
                // Close the modal when user clicks anywhere outside of it
                window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = 'none';
                    }
                }
            }
        </script>";
} else {
    // Handle the case when the user is not logged in
    echo "<p>Welcome Guest</p>";
}
?>


</div>

</div>

<div class="container-body-content">
	<!-- set to fetch the page home.php, automatically and when another page is fetched should replace the corrent fetched page in here-->
</div>
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Upload Your Signature</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="file" name="signature_file"><br>
            <input type="submit" value="Upload" name="submit" style="margin-top: 10px;">
        </form>
    </div>
</div>

<!-- Modal for managing account -->
<div id="manageAccountModal" class="modal">
    <div class="modal-content">
        <span class="closec">&times;</span>
        <div class="container">
            <?php 

//session_start();
include 'include.php'; // Include your database connection script

// Validate session
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login if session username is not set
    exit();
}

// Fetch username from session
$username = $_SESSION['username'];

// Fetch id from employee_access table based on concatenated first_name and last_name
$id = '';
$sql_select_id = "SELECT id, first_name, last_name FROM employee_access";
$result = $conn->query($sql_select_id);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $name = $row['first_name'] . $row['last_name'];
        if ($name === $username) {
            $id = $row['id'];
            break;
        }
    }
}

echo htmlspecialchars($id); ?>
            <form id="updateAccountForm" method="POST" action="manage_account.php">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" readonly value="<?php echo htmlspecialchars($username); ?>"><br><br>

                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required><br><br>

                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required><br><br>

                <input type="submit" value="Update Password">
            </form>
        </div>
    </div>
</div>




<div class="all-container">
    
   <!-- Your existing HTML content -->
</div>
<?php
// session_start(); // Uncomment this if the session is not started earlier in the code

// Include database connection
include 'include.php';

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Check if the username is set in the session
    if (isset($_SESSION['username'])) {
        $Username = $_SESSION['username'];

        // Specify the directory where you want to store the images
        $upload_directory = "signature_images/" . $Username . "/";

        // Check if the directory exists, and if not, attempt to create it
        if (!file_exists($upload_directory)) {
            // Attempt to create the directory
            if (!mkdir($upload_directory, 0777, true) && !is_dir($upload_directory)) {
                // Directory creation failed, handle the error
                $error_message = "Failed to create directory: $upload_directory";
                error_log($error_message); // Log the error to the PHP error log
                echo $error_message; // Display the error to the user
                exit; // Stop execution if directory creation fails
            }
        }

        // Get the uploaded file details
        $file_name = $_FILES['signature_file']['name'];
        $file_tmp = $_FILES['signature_file']['tmp_name'];
        $file_type = $_FILES['signature_file']['type'];
        $file_size = $_FILES['signature_file']['size'];

        // Check if file is uploaded successfully
        if ($file_tmp) {
            // Check if the file is an image
            $allowed_types = array("image/jpeg", "image/png");
            if (in_array($file_type, $allowed_types)) {
                // Generate a unique filename to prevent conflicts
                $unique_filename = uniqid() . "_" . $file_name;
                // Move the uploaded file to the specified directory
                $destination = $upload_directory . $unique_filename;
                if (move_uploaded_file($file_tmp, $destination)) {
                    // Get employee_id from employee_access table
                    $sql_get_employee_id = "SELECT id FROM employee_access WHERE username = ?";
                    $stmt_get_employee_id = $conn->prepare($sql_get_employee_id);
                    if ($stmt_get_employee_id) {
                        $stmt_get_employee_id->bind_param("s", $Username);
                        $stmt_get_employee_id->execute();
                        $result_get_employee_id = $stmt_get_employee_id->get_result();
                        
                        if ($result_get_employee_id->num_rows > 0) {
                            $employee = $result_get_employee_id->fetch_assoc();
                            $employee_id = $employee['id'];
                            
                            // Check if the user already has a signature
                            $sql_check = "SELECT signature_path FROM signature WHERE employee_id = ?";
                            $stmt_check = $conn->prepare($sql_check);
                            if ($stmt_check) {
                                $stmt_check->bind_param("i", $employee_id);
                                $stmt_check->execute();
                                $result_check = $stmt_check->get_result();

                                if ($result_check->num_rows === 0) {
                                    // Insert new signature
                                    $sql_insert = "INSERT INTO signature (Username, employee_id, signature_path, has_uploaded_signature) VALUES (?, ?, ?, 1)";
                                    $stmt_insert = $conn->prepare($sql_insert);
                                    if ($stmt_insert) {
                                        $stmt_insert->bind_param("sis", $Username, $employee_id, $destination);
                                        $stmt_insert->execute();

                                        // Check if the insert was successful
                                        if ($stmt_insert->affected_rows > 0) {
                                            echo "<script>
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Signature Created Successfully',
                                                    showConfirmButton: false,
                                                    timer: 3000
                                                }).then(function () {
                                                    window.location.href = 'dashboard.php';
                                                });
                                            </script>";
                                        } else {
                                            // Display MySQL error for debugging
                                            echo "Failed to insert signature. Error: " . $conn->error;
                                        }
                                        $stmt_insert->close();
                                    } else {
                                        echo "Failed to prepare insert statement. Error: " . $conn->error;
                                    }
                                } else {
                                    // Update existing signature
                                    $sql_update = "UPDATE signature SET signature_path = ?, has_uploaded_signature = 1 WHERE employee_id = ?";
                                    $stmt_update = $conn->prepare($sql_update);
                                    if ($stmt_update) {
                                        $stmt_update->bind_param("si", $destination, $employee_id);
                                        $stmt_update->execute();

                                        // Check if the update was successful
                                        if ($stmt_update->affected_rows > 0) {
                                            echo "<script>
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Signature Updated Successfully',
                                                    showConfirmButton: false,
                                                    timer: 3000
                                                }).then(function () {
                                                    window.location.href = 'dashboard.php';
                                                });
                                            </script>";
                                        } else {
                                            echo "Failed to update signature. Error: " . $conn->error;
                                        }
                                        $stmt_update->close();
                                    } else {
                                        echo "Failed to prepare update statement. Error: " . $conn->error;
                                    }
                                }
                                $stmt_check->close();
                            } else {
                                echo "Failed to prepare check statement. Error: " . $conn->error;
                            }
                        } else {
                            echo "Failed to retrieve employee_id.";
                        }
                        $stmt_get_employee_id->close();
                    } else {
                        echo "Failed to prepare get employee_id statement. Error: " . $conn->error;
                    }
                } else {
                    echo "Failed to move uploaded file to destination directory.";
                }
            } else {
                echo "Invalid file format. Please upload a JPEG or PNG image.";
            }
        } else {
            echo "Failed to upload file.";
        }
    } else {
        echo "Username not set in session.";
    }
}
?>
<script>


    

 //for incidence
 function loadincidencedetails(url) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(".container-body-content").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", url, true);
    xhttp.send();
}
//for ticket loadticketdetails
 function loadticketdetails(url) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(".container-body-content").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", url, true);
    xhttp.send();
}
//for report loadreportdetails

 function loadreportdetails(url) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(".container-body-content").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", url, true);
    xhttp.send();
}
function loadexpDetails(url) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(".container-body-content").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", url, true);
    xhttp.send();
}
//for safari
function loadsafDetails(url) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(".container-body-content").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", url, true);
    xhttp.send();
}
//for memo request
function loadmemoDetails(url) {

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(".container-body-content").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", url, true);
    xhttp.send();
}
//home page loadhomeDetails
function loadhomeDetails(url) {

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(".container-body-content").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", url, true);
    xhttp.send();
}
//for approve memo 
function loadmemoaprdetails(url) {

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(".container-body-content").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", url, true);
    xhttp.send();
}
//for incidence
function navigatetoaddincidence(url) {

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(".container-body-content").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", url, true);
    xhttp.send();
}


        /*function navigatetoaddincidence(page) {
            // Redirect to the specified page
            window.location.href = page;
        }*/
//for user
function loaduserDetails(url) {

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(".container-body-content").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", url, true);
    xhttp.send();
}
     
//for previos and next buttons 
        function nextStep(step) {
            document.getElementById('step-' + step).style.display = 'none';
            document.getElementById('step-' + (step + 1)).style.display = 'block';
        }

        function prevStep(step) {
            document.getElementById('step-' + step).style.display = 'none';
            document.getElementById('step-' + (step - 1)).style.display = 'block';
        }
        

//FETCH ACCESS FORM
    //for memo request
function loadmACCESSDetails(url) {

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(".container-body-content").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", url, true);
    xhttp.send();
}
//FOR ASSET loadmASSETDetails
function loadmASSETDetails(url) {

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(".container-body-content").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", url, true);
    xhttp.send();
}

//FOR REQ loadmREQASSETDetails
function loadmREQASSETDetails(url) {

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(".container-body-content").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", url, true);
    xhttp.send();
}
</script>
 <script>
        document.getElementById('navigatesafari').addEventListener('click', function() {
            window.location.href = 'create_safari_imprest.php'; // Replace with your desired URL
        });
    </script>

   <script>
    function openEditMemoPopup(memoId) {
        // Display the overlay and popup
        document.getElementById('overlay').style.display = 'block';
        document.getElementById('editMemoPopup').style.display = 'block';

        // Set the memo ID (if needed)
        document.getElementById('memoId').innerText = 'Memo ID: ' + memoId;

        // Fetch content from edit_memo.php via AJAX (optional)
        fetch('edit_memo.php?id=' + memoId)
            .then(response => response.text())
            .then(data => {
                document.querySelector('#editMemoPopup .popup-content').innerHTML = data;
            })
            .catch(error => console.error('Error fetching memo content:', error));
    }

    function closeEditMemoPopup() {
        // Hide the overlay and popup
        document.getElementById('overlay').style.display = 'none';
        document.getElementById('editMemoPopup').style.display = 'none';
    }


    //for retire 
     function showPopupretire(rowData) {
            document.getElementById('applicant-name').value = rowData.username;
            document.getElementById('designation').value = rowData.Position_name;
            document.getElementById('department').value = rowData.department_name;
            document.getElementById('claim-nature').value = '';
            //document.getElementById('claimant-signature').value = '';
            document.getElementById('claim-date').value = new Date().toISOString().split('T')[0];
            document.getElementById('imprest-reference-code').value = rowData.imprest_id;

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
<script>
        document.addEventListener("DOMContentLoaded", function() {
            var modal = document.getElementById('manageAccountModal');
            var manageAccountLink = document.querySelector('.manage_account_link');
            var closeBtn = document.getElementsByClassName("closec")[0];

            manageAccountLink.onclick = function(event) {
                event.preventDefault();
                modal.style.display = "block";
            }

            closeBtn.onclick = function() {
                modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        });
    </script>
<script>
function deleteMemo(id) {
    if (confirm("Are you sure you want to delete this record?")) {
        // Perform AJAX request
        $.ajax({
            url: 'Memo_request.php',
            type: 'POST',
            data: {
                action: 'delete',
                id: id
            },
            success: function(response) {
                if (response == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'The record has been deleted successfully.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    // Optionally, you can reload or update the page after deletion
                    location.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed!',
                        text: 'Failed to delete the record. Please try again.',
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred while deleting the record.',
                });
            }
        });
    }
    return false; // Prevent the default action of the link
}
</script><script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to load content into the container-body-content div
    function loadPage(url) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.onload = function() {
            if (this.status == 200) {
                document.querySelector('.container-body-content').innerHTML = this.responseText;
            } else {
                console.error('Failed to fetch page: ' + url);
            }
        };
        xhr.onerror = function() {
            console.error('Request error while fetching page: ' + url);
        };
        xhr.send();
    }

    // Initially load home.php
    loadPage('report_view.php');

    // Add click event listeners to navigation links
    document.querySelectorAll('.nav-link').forEach(function(link) {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const url = this.getAttribute('href');
            loadPage(url);
        });
    });
});
</script>
<script>
function replaceEmptyCells() {
    // Select all table cells in the popup
    const cells = document.querySelectorAll('.popup-content td');

    cells.forEach(cell => {
        // Check if the cell is empty or contains only whitespace
        if (!cell.textContent.trim()) {
            cell.textContent = 'N/A';
            cell.classList.add('empty');
            cell.classList.remove('non-empty');
        } else {
            cell.classList.add('non-empty');
            cell.classList.remove('empty');
        }
    });
}
    
function showPopup2(event, employeeId) {
    event.preventDefault(); // Prevent the default anchor behavior

    // Show the popup
    document.getElementById('popup').style.display = 'flex';

    // Fetch and display details
    fetchDetails(employeeId);
}

function closePopup() {
    document.getElementById('popup').style.display = 'none';
}

function fetchDetails(employeeId) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'fetch_asset_details.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            document.getElementById('popup-details').innerHTML = xhr.responseText;
            replaceEmptyCells(); // Replace empty cells with "N/A" after details are loaded
        } else {
            document.getElementById('popup-details').innerHTML = 'Error fetching details.';
        }
    };
    xhr.send('employee_id=' + encodeURIComponent(employeeId));
}

function showRequestFields() {
    // Hide all dynamic fields
    document.querySelectorAll('.dynamic-field').forEach(field => field.classList.remove('show'));
    
    // Get the selected request type
    const requestType = document.getElementById('request_type').value;

    // Show fields based on the selected request type
    if (requestType === 'new') {
        document.getElementById('newFields').classList.add('show');
    } else if (requestType === 'exchange') {
        document.getElementById('exchangeFields').classList.add('show');
    } else if (requestType === 'shifting') {
        document.getElementById('shiftingFields').classList.add('show');
    } else if (requestType === 'addition') {
        document.getElementById('additionFields').classList.add('show');
    }
}
</script>

 <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('fetch_report_view.php')
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.querySelector('#data-table tbody');
                    data.forEach(row => {
                        const tr = document.createElement('tr');
                        Object.values(row).forEach(cell => {
                            const td = document.createElement('td');
                            td.textContent = cell;
                            tr.appendChild(td);
                        });

                        // Add eye icon in the "View" column
                        const viewTd = document.createElement('td');
                        const eyeIcon = document.createElement('i');
                        eyeIcon.classList.add('fas', 'fa-eye'); // Font Awesome classes for the eye icon
                        eyeIcon.style.cursor = 'pointer';
                        eyeIcon.addEventListener('click', () => {
                            if (['imprest_safari', 'imprest_expenditure', 'incidents', 'memos', 'retirement'].includes(row.request_type)) {
                                // Fetch data from the specific table
                                fetch(`fetch_details_for_table.php?table=${row.request_type}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        const modalTableBody = document.querySelector('#modal-content tbody');
                                        const modalTableHead = document.querySelector('#modal-content thead');
                                        modalTableBody.innerHTML = ''; // Clear previous content
                                        modalTableHead.innerHTML = ''; // Clear previous headers
                                        if (data.rows.length > 0) {
                                            // Create table headers
                                            const headers = data.columns;
                                            const tr = document.createElement('tr');
                                            headers.forEach(header => {
                                                const th = document.createElement('th');
                                                th.textContent = header;
                                                tr.appendChild(th);
                                            });
                                            modalTableHead.appendChild(tr);

                                            // Populate table rows
                                            data.rows.forEach(row => {
                                                const tr = document.createElement('tr');
                                                Object.values(row).forEach(cell => {
                                                    const td = document.createElement('td');
                                                    td.textContent = cell;
                                                    tr.appendChild(td);
                                                });
                                                modalTableBody.appendChild(tr);
                                            });
                                        }
                                        document.querySelector('#myModal').style.display = 'block';
                                    })
                                    .catch(error => console.error('Error fetching table data:', error));
                            } else {
                                // Display only the request_type
                                document.querySelector('#modal-content pre').textContent = row.request_type;
                                document.querySelector('#myModal').style.display = 'block';
                            }
                        });
                        viewTd.appendChild(eyeIcon);
                        tr.appendChild(viewTd);

                        tableBody.appendChild(tr);
                    });
                });
        });

        // Close the modal when the user clicks anywhere outside of the modal content
        window.addEventListener('click', function(event) {
            const modal = document.querySelector('#myModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        });
    </script>
    <script>
    function showRequestFields() {
        // Hide all dynamic fields
        document.querySelectorAll('.dynamic-field').forEach(field => field.classList.remove('show'));
        
        // Get the selected request type
        const requestType = document.getElementById('request_type').value;

        // Show fields based on the selected request type
        if (requestType === 'new') {
            document.getElementById('newFields').classList.add('show');
        } else if (requestType === 'exchange') {
            document.getElementById('exchangeFields').classList.add('show');
        } else if (requestType === 'shifting') {
            document.getElementById('shiftingFields').classList.add('show');
        } else if (requestType === 'addition') {
            document.getElementById('additionFields').classList.add('show');
        }
    }
</script>
  


<script>
    // Function to fetch employee names
    function fetchEmployeeNames() {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "fetch_employee_names_for_asset.php", true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);
                var employeeNamesSelect = document.getElementById("employee_names");
                employeeNamesSelect.innerHTML = "";
                response.forEach(function(employee) {
                    var option = document.createElement("option");
                    option.value = employee.id;
                    option.textContent = employee.id + " - " + employee.first_name + " " + employee.last_name;
                    employeeNamesSelect.appendChild(option);
                });
                employeeNamesSelect.style.display = "block";
            }
        };
        xhr.send();
    }

    // Function to set the employee ID to the assigned_to input field
    function setEmployeeId(selectElement) {
        document.getElementById("assigned_to").value = selectElement.value;
        selectElement.style.display = "none";
    }

    // Get current date
    var today = new Date().toISOString().slice(0, 10);
    // Set today's date as the default value for the purchase date input field
    document.getElementById("purchase_date").value = today;
</script>


  <script>
function showPopup(event) {
    event.preventDefault(); // Prevent default link behavior
    var popup = document.getElementById('popup');
    var popupBody = document.getElementById('popup-body');

    // Fetch content from add_asset.php and display it in the popup
    fetch('add_asset.php')
        .then(response => response.text())
        .then(data => {
            popupBody.innerHTML = data; // Insert fetched content into the popup
            popup.style.display = 'flex'; // Show the popup
        })
        .catch(error => {
            console.error('Error fetching the content:', error);
        });
}

function closePopup() {
    document.getElementById('popup').style.display = 'none'; // Hide the popup
}

document.addEventListener('DOMContentLoaded', function() {
    var popup = document.getElementById('popup');
    var closeBtn = document.querySelector('.close-btn');

    // Handle the close button click
    closeBtn.addEventListener('click', function() {
        closePopup();
    });

    // Close popup when clicking outside of the popup content
    window.addEventListener('click', function(e) {
        if (e.target === popup) {
            closePopup();
        }
    });
});

</script>
<script>
         function showPopup3(element) {
            var id = element.getAttribute('data-id');
            
            // Fetch and display content for the popup
            fetchPopupContent(id);
            
            // Show the popup modal
            var modal = document.getElementById("popupModal");
            modal.style.display = "block";
          // Close the modal when the close button is clicked
    var closeButton = modal.getElementsByClassName("close")[0];
    closeButton.onclick = function() {
        modal.style.display = "none";
    };
        }

        function fetchPopupContent(id) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "view_request_asset.php?id=" + id, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.getElementById('popupContent').innerHTML = xhr.responseText;
                } else {
                    console.error('Failed to fetch asset details');
                }
            };
            xhr.send();
        }

        // Close the modal when the close button is clicked
        var closeButton = document.getElementsByClassName("closea")[0];
        closeButton.onclick = function() {
            var modal = document.getElementById("popupModal");
            modal.style.display = "none";
        };

        // Close the modal if clicked outside of the modal
        window.onclick = function(event) {
            var modal = document.getElementById("popupModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };
    </script>
<script>
function openApprText() {
    document.getElementById('approvalModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('approvalModal').style.display = 'none';
}

function submitComment() {
    const comment = document.getElementById('approvalComment').value;
    const request_id = document.getElementById('request_id').value;
    
    if (comment.trim() === "") {
        Swal.fire({
            icon: 'warning',
            title: 'Oops...',
            text: 'Please enter a comment.',
        });
        return;
    }

    // Perform AJAX request
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "update_request_status.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                 // Successfully updated
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Comment submitted and status updated for Request ID: ' + request_id,
                }).then(() => {
                    closeModal();
                    // Optionally, you can refresh the page or update the UI here
                });
            } else {
                 Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error updating the request.',
                });
            }
        }
    };
    xhr.send(`request_id=${encodeURIComponent(request_id)}&comment=${encodeURIComponent(comment)}`);
}

// Close the modal when clicking outside of it
window.onclick = function(event) {
    if (event.target === document.getElementById('approvalModal')) {
        closeModal();
    }
}
</script>
         <script>
        function openDeclText() {
            document.getElementById('declineModal').style.display = 'flex';
        }

        function closeModald() {
            document.getElementById('declineModal').style.display = 'none';
        }

        function submitCommentc() {
    const comment = document.getElementById('declineComment').value;
    const request_id = document.getElementById('request_id').value;
    
    if (comment.trim() === "") {
        Swal.fire({
            icon: 'warning',
            title: 'Oops...',
            text: 'Please enter a comment.',
        });
        return;
    }

    // Perform AJAX request
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "update_request_status_decline.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                 // Successfully updated
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Comment submitted and status updated for Request ID: ' + request_id,
                }).then(() => {
                    closeModald();
                    // Optionally, you can refresh the page or update the UI here
                });
            } else {
                 Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error updating the request.',
                });
            }
        }
    };
    xhr.send(`request_id=${encodeURIComponent(request_id)}&comment=${encodeURIComponent(comment)}`);
}

        // Close the modal when clicking outside of it
        window.onclick = function(event) {
            if (event.target === document.getElementById('declineModal')) {
                closeModald();
            }
        }
    </script>
<script>
function loadMemoRequest() {
    var date = document.getElementById('search_date').value;
    var url = "Memo_request.php";

    if (date) {
        url += "?search_date=" + encodeURIComponent(date);
    }

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(".container-body-content").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", url, true);
    xhttp.send();
}
</script>
</body>
</html>
