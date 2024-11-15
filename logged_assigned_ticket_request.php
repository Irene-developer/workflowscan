<?php
// Include your database connection settings
include 'include.php'; // Make sure this file sets up $conn
include('session_timeout.php');

// Check if the department_name and Position_name are set in session
if (isset($_SESSION['department_name']) && isset($_SESSION['username']) || isset($_SESSION['id'])) {
    // Retrieve department_name and Position_name from session
    $department_name = $_SESSION['department_name'];
    $username = $_SESSION['username'];
    $employee_id = $_SESSION['id'];
}

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged Tickets with Ticket Form</title>
    
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

    <div class="ticket-table-container">
        <button onclick="window.location.href='dashboard.php'" class="back-btn">Back to Dashboard</button>
       
<?php
// Set the number of results per page
$resultsPerPage = 8;

// Determine the total number of pages
$sql = "SELECT COUNT(*) AS total FROM log_tickets WHERE assigned_to = ?";
$stmt = $conn->prepare($sql);

// Check if prepare() was successful
if ($stmt === false) {
    die('Error preparing statement: ' . $conn->error); // Output error details
}

$employee_id = $_SESSION['id']; // Assuming employee_id is stored in the session
$stmt->bind_param('i', $employee_id);

// Execute the statement
if (!$stmt->execute()) {
    die('Error executing statement: ' . $stmt->error); // Output error details
}

$result = $stmt->get_result();
$totalResults = $result->fetch_assoc()['total'];
$totalPages = ceil($totalResults / $resultsPerPage);

// Determine the current page number
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($currentPage > $totalPages) $currentPage = $totalPages;
if ($currentPage < 1) $currentPage = 1;

// Calculate the offset
$offset = ($currentPage - 1) * $resultsPerPage;

// Query to fetch tickets with pagination and joined employee_access for username
$sql = "
    SELECT log_tickets.*, employee_access.username 
    FROM log_tickets 
    JOIN employee_access ON employee_access.id = log_tickets.assigned_to 
    WHERE log_tickets.assigned_to = ? 
    LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);

// Check if prepare() was successful
if ($stmt === false) {
    die('Error preparing statement: ' . $conn->error); // Output error details
}

$stmt->bind_param('iii', $employee_id, $resultsPerPage, $offset);

// Execute the statement
if (!$stmt->execute()) {
    die('Error executing statement: ' . $stmt->error); // Output error details
}

$result = $stmt->get_result();

// Close the statement and connection
$stmt->close();
$conn->close();
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
    <link rel="stylesheet" href="assets/fas fas fas 3/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/quill/quill.snow.css">
    <!-- Scripts -->
    <script src="assets/quill/quill.min.js"></script>
    <script src="assets/js/jquery/jquery-3.7.1.min.js"></script>
    <script src="assets/sweetalert2/sweetalert2.all.min.js"></script>
<script>
// Function to open the popup
function openmyviewPopup(ticketId) {
    const popup = document.getElementById('viewTicketPopup');
    const xhr = new XMLHttpRequest();
    
    // AJAX request to fetch ticket details
    xhr.open('GET', 'fetch_ticket_details_assigned.php?id=' + encodeURIComponent(ticketId), true);
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
