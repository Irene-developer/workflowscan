<?php
// Include your database connection settings
include 'include.php'; // Make sure this file sets up $conn
include('session_timeout.php');

// Check if the department_name and Position_name are set in session
if (isset($_SESSION['department_name']) && isset($_SESSION['username'])) {
    // Retrieve department_name and Position_name from session
    $department_name = $_SESSION['department_name'];
    $username = $_SESSION['username'];
}

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged Tickets with Ticket Form</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet" type="text/css" href="styles_css_log_ticket.css">
    <link rel="stylesheet" href="assets/fas fas fas 3/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/quill/quill.snow.css">
    <!-- Scripts -->
    <script src="assets/quill/quill.min.js"></script>
    <script src="assets/js/jquery/jquery-3.7.1.min.js"></script>
    <script src="assets/sweetalert2/sweetalert2.all.min.js"></script>
</head>
<body>
<?php include 'header.php'; ?>
    <!-- Ticket Table Section -->
       <!-- Plus Icon outside and above container -->
    <i class="add-ticket-icon" onclick="openPopup()">&#43;</i> <!-- Plus Icon for adding ticket -->

    <div class="ticket-table-container">
        <button onclick="window.location.href='dashboard.php'" class="back-btn">Back to Dashboard</button>
       
<?php
// Set the number of results per page
$resultsPerPage = 8;

// Determine the total number of pages
$sql = "SELECT COUNT(*) AS total FROM log_tickets WHERE username = ?";
$stmt = $conn->prepare($sql);
$username = $_SESSION['username']; // Assuming username is stored in the session
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$totalResults = $result->fetch_assoc()['total'];
$totalPages = ceil($totalResults / $resultsPerPage);

// Determine the current page number
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($currentPage > $totalPages) $currentPage = $totalPages;
if ($currentPage < 1) $currentPage = 1;

// Calculate the offset
$offset = ($currentPage - 1) * $resultsPerPage;

// Query to fetch tickets with pagination
$sql = "SELECT * FROM log_tickets WHERE username = ? LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sii', $username, $resultsPerPage, $offset);
$stmt->execute();
$result = $stmt->get_result();
?>


<table>
    <thead>
        <tr>
            <th>Ticket ID</th>
            <th>Title</th>
            <th>Category</th>
            <th>Urgency</th>
            <th>Created at</th>
            <th>Status</th>
            <th>File Attached</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Check if there are results
        if ($result->num_rows > 0) {
            // Output data for each row
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['ticket_id']) . '</td>';
                echo '<td>' . htmlspecialchars($row['title']) . '</td>';
                echo '<td>' . htmlspecialchars($row['category']) . '</td>';
                echo '<td>' . htmlspecialchars($row['urgency']) . '</td>';
                echo '<td>' . htmlspecialchars($row['created_at']) . '</td>';
                echo '<td class="status ' . 'status-' . strtolower(str_replace(' ', '-', htmlspecialchars($row['status']))) . '">';
                echo htmlspecialchars($row['status']);
                echo '</td>';

                // File link
                echo '<td>';
                if (!empty($row['file_path'])) {
                    echo '<a href="#" data-file="' . htmlspecialchars($row['file_path']) . '" class="view-file-link">View File</a>';
                }
                echo '</td>';

                // Action buttons
                echo '<td>';
                echo '<a href="view_ticket.php?id=' . htmlspecialchars($row['ticket_id']) . '" class="view-btn">View</a> | ';
               echo '<a href="delete_ticket.php?id=' . htmlspecialchars($row['ticket_id']) . '" class="delete-btn" onclick="return confirm(\'Are you sure you want to delete this ticket?\');">Delete</a>';
                echo '</td>';

                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="8">No tickets found</td></tr>';
        }
        ?>
    </tbody>
</table>

<!-- Pagination Controls -->
<div class="pagination">
    <?php
    // Previous Page Link
    if ($currentPage > 1) {
        echo '<a href="?page=' . ($currentPage - 1) . '">&laquo; Previous</a>';
    }

    // Page Numbers
    for ($page = 1; $page <= $totalPages; $page++) {
        if ($page == $currentPage) {
            echo '<span class="current">' . $page . '</span>';
        } else {
            echo '<a href="?page=' . $page . '">' . $page . '</a>';
        }
    }

    // Next Page Link
    if ($currentPage < $totalPages) {
        echo '<a href="?page=' . ($currentPage + 1) . '">Next &raquo;</a>';
    }
    ?>
</div>

<?php
// Close the connection
$conn->close();
?>

    </div>
<?php
// Include your database connection settings and PHPMailer
include 'include.php'; // Ensure this file sets up $conn

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown'; // Get username from session
    $title = $_POST['title'];
    $description = $_POST['description'];
    $urgency = $_POST['urgency'];
    $category = $_POST['category'];

    // Handle file upload
    $filePath = null;
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads_tickets/';
        $uploadFile = $uploadDir . basename($_FILES['file']['name']);
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
            $filePath = $uploadFile;
        } else {
            die("File upload failed.");
        }
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO log_tickets (username, title, description, urgency, category, file_path) VALUES (?, ?, ?, ?, ?, ?)");
    
    if ($stmt) {
        $stmt->bind_param('ssssss', $username, $title, $description, $urgency, $category, $filePath);

        // Execute the query
        if ($stmt->execute()) {
            // Get the last inserted ID
            $ticket_id = $conn->insert_id;

            // Fetch user email from employee_access table
            $emailQuery = $conn->prepare("SELECT email FROM employee_access WHERE username = ?");
            $emailQuery->bind_param('s', $username);
            $emailQuery->execute();
            $emailQuery->bind_result($userEmail);
            $emailQuery->fetch();
            $emailQuery->close();
/*

172.18.155.32
notifications@kcblbank.co.tz
Balancesheet@2025

*/
  
            // Send email to user
            if (!empty($userEmail)) {
                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->Host = '172.18.155.32'; // Set the SMTP server to send through
                $mail->SMTPAuth = true;
                $mail->Username = 'notifications@kcblbank.co.tz'; // SMTP username
                $mail->Password = 'Balancesheet@2026'; // SMTP password
               // $mail->SMTPSecure = 'tls';
                $mail->Port = 25;

                // Set email format
                $mail->setFrom('notifications@kcblbank.co.tz', 'Service Desk');
                $mail->addAddress($userEmail); // Send to user's email
                $mail->Subject = "Service Request Ticket Submitted";
                $mail->Body = "Your Service Request Ticket with Ticket ID $ticket_id has been submitted successfully.";

                if (!$mail->send()) {
                    echo "User email could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            }

            // Send email to ICT Support
            $ictMail = new PHPMailer();
            $ictMail->isSMTP();
            $ictMail->Host = '172.18.155.32';
            $ictMail->SMTPAuth = true;
            $ictMail->Username = 'notifications@kcblbank.co.tz';
            $ictMail->Password = 'Balancesheet@2025';
            //$ictMail->SMTPSecure = 'tls';
            $ictMail->Port = 25;

            $ictMail->setFrom('notifications@kcblbank.co.tz', 'Service Desk');
            $ictMail->addAddress('KCBL_ICT_SUPPORT@kcblbank.co.tz'); // Send to ICT support
            $ictMail->Subject = "New Service Request Ticket";
            $ictMail->Body = "There is a submitted Service Request with Ticket ID $ticket_id from username $username requiring your action.";

            if (!$ictMail->send()) {
                echo "Support email could not be sent. Mailer Error: {$ictMail->ErrorInfo}";
            }

            // Success message with SweetAlert
            echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Your Ticket With Id $ticket_id Submitted Successfully',
                            showConfirmButton: true
                        }).then(function () {
                            window.location.href = 'dashboard.php';
                        });
                    </script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    // Close connection
    $conn->close();
}
?>



<!-- Popup for ICT Ticket Form -->
<div class="popup" id="popup">
    <div class="ticket-form-container">
        <span class="popup-close">&times;</span>
        <h2>ICT Ticket Request Form</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title/Subject of the Issue</label>
                <input type="text" id="title" name="title" placeholder="Enter the issue title" required>
            </div>
            
            <div class="form-group">
                <label for="description">Detailed Description</label>
                <textarea id="description" name="description" placeholder="Describe the issue/request" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="urgency">Urgency Level</label>
                <select id="urgency" name="urgency" required>
                    <option value="" disabled selected>Select urgency level</option>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                    <option value="Critical">Critical</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="category">Select Category</label>
                <select id="category" name="category" required>
                    <option value="" disabled selected>Select a category</option>
                    <option value="Hardware">Hardware</option>
                    <option value="Software">Software</option>
                    <option value="Network">Network</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="file">Attach File/Screenshot</label>
                <input type="file" id="file" name="file">
            </div>
            
            <button type="submit" class="submit-btn">Submit Ticket</button>
        </form>
    </div>
</div>
<!-- View Ticket Popup -->
<div id="viewTicketPopup" class="myviewpopup">
    <div class="myviewpopup-content">
        <span class="close-myviewpopup">&times;</span>
        <div id="ticketDetails">
            <!-- Ticket details will be dynamically loaded here -->
        </div>
    </div>
</div>

<!-- The Modal for Viewing Files -->
<div id="fileViewModal" class="modal">
    <div class="modal-content">
        <span class="modal-close">&times;</span>
        <iframe id="fileView" width="100%" height="500px"></iframe>
    </div>
</div>

<script>
// Function to open the popup
function openmyviewPopup(ticketId) {
    const popup = document.getElementById('viewTicketPopup');
    const xhr = new XMLHttpRequest();
    
    // AJAX request to fetch ticket details
    xhr.open('GET', 'fetch_ticket_details.php?id=' + encodeURIComponent(ticketId), true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            document.getElementById('ticketDetails').innerHTML = xhr.responseText;
            popup.style.display = 'flex';
        } else {
            console.error('Failed to fetch ticket details');
        }
    };
    xhr.send();
}

// Function to close the popup
function closePopup() {
    document.getElementById('viewTicketPopup').style.display = 'none';
}

// Attach event listener to close button
document.querySelector('.close-myviewpopup').addEventListener('click', closePopup);

// Close the popup if clicked outside of the content
window.onclick = function(event) {
    if (event.target === document.getElementById('viewTicketPopup')) {
        closePopup();
    }
}

// Attach event listeners to "View" links
document.querySelectorAll('.view-btn').forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault();
        const ticketId = this.getAttribute('href').split('=')[1]; // Extract ticket ID from href
        openmyviewPopup(ticketId);
    });
});
</script>


<!-- JavaScript to handle popups and modals -->
<script>
    // Open the popup
    function openPopup() {
        document.getElementById('popup').style.display = 'flex';
    }

    // Close the popup
    function closePopup() {
        document.getElementById('popup').style.display = 'none';
    }

    // Handle click outside the popup to close it
    window.onclick = function(event) {
        var popup = document.getElementById('popup');
        if (event.target === popup) {
            closePopup();
        }

        var modal = document.getElementById('fileViewModal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    }

    // Handle the file view modal
    document.querySelectorAll('a[data-file]').forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const fileUrl = this.getAttribute('data-file');
            const modal = document.getElementById('fileViewModal');
            const iframe = document.getElementById('fileView');
            iframe.src = fileUrl;
            modal.style.display = "block";
        });
    });

    // Close the file view modal
    var modalClose = document.getElementsByClassName('modal-close')[0];
    modalClose.onclick = function() {
        document.getElementById('fileViewModal').style.display = 'none';
    }
</script>

    <?php include 'footer.php'; ?>
</body>
</html>
