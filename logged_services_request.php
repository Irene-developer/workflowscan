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
    
    <link rel="stylesheet" type="text/css" href="styles_css_log_ticket.css">
    <link rel="stylesheet" href="assets/fas fas fas 3/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/quill/quill.snow.css">
    <!-- Scripts -->
    <script src="assets/quill/quill.min.js"></script>
    <script src="assets/js/jquery/jquery-3.7.1.min.js"></script>
    <script src="assets/sweetalert2/sweetalert2.all.min.js"></script>
    <style>    
/* Container styling */
.assign_with_back_btn {
    display: flex;
    justify-content: space-between; /* Align items to the start and end of the container */
    align-items: center; /* Center items vertically within the container */
    padding: 10px; /* Add padding around the container */
}

/* Group container for input and button */
.assign-group {
    display: flex;
    align-items: center; /* Center items vertically within the group */
}

/* Input field styling */
.assign-group input {
    margin-right: 10px; /* Add space between the input and the button */
    padding: 8px 12px; /* Add padding inside the input */
    border: 1px solid #ddd; /* Light border color */
    border-radius: 4px; /* Rounded corners */
    font-size: 14px; /* Font size for readability */
    outline: none; /* Remove default outline */
    transition: border-color 0.3s ease; /* Smooth transition for border color */
}

.assign-group input:focus {
    border-color: #3385ff; /* Change border color on focus */
}

/* Button styling */
.assign-group .assign-btn {
    background-color: #3385ff; /* Primary button color */
    color: white; /* Button text color */
    border: none; /* Remove default border */
    padding: 8px 16px; /* Add padding inside the button */
    border-radius: 4px; /* Rounded corners */
    font-size: 14px; /* Font size for readability */
    cursor: pointer; /* Change cursor to pointer */
    transition: background-color 0.3s ease, box-shadow 0.3s ease; /* Smooth transitions */
    margin: 2px;
}

.assign-group .assign-btn:hover {
    background-color: #287bff; /* Darker color on hover */
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2); /* Slightly darker shadow on hover */
}

.assign-group .assign-btn:active {
    background-color: #1e66d0; /* Even darker color when button is pressed */
}
/* Add this to your styles.css */
.suggestions {
    position: absolute;
    
    background-color: white;
    max-height: 200px;
    
    z-index: 1000;
    width: 15%;
    
}

.suggestion-item {
    padding: 8px 12px;
    cursor: pointer;
}

.suggestion-item:hover {
    background-color: #f0f0f0;
}

    </style>
</head>
<body>
<?php include 'header.php'; ?>
   
    <div class="ticket-table-container">
    <div class="assign_with_back_btn">
        <button onclick="window.location.href='dashboard.php'" class="back-btn">Back to Dashboard</button>
        <div class="assign-group">
            <button class="assign-btn" >Assign To</button>
            <input type="text" id="assignInput" name="assign" placeholder="Enter assignment" onfocus="fetchNames()">
            <div id="suggestions" class="suggestions"></div>
        </div>
    </div>


        
       
  <?php
// Set the number of results per page
$resultsPerPage = 8;

// Determine the total number of pages
$sql = "SELECT COUNT(*) AS total FROM log_tickets";
$result = $conn->query($sql);
$totalResults = $result->fetch_assoc()['total'];
$totalPages = ceil($totalResults / $resultsPerPage);

// Determine the current page number
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($currentPage > $totalPages) $currentPage = $totalPages;
if ($currentPage < 1) $currentPage = 1;

// Calculate the offset
$offset = ($currentPage - 1) * $resultsPerPage;

// Query to fetch tickets with pagination
$sql = "SELECT * FROM log_tickets LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $resultsPerPage, $offset);
$stmt->execute();
$result = $stmt->get_result();
?>

<table>
    <thead>
        <tr>
             <!-- Add a column header for checkboxes -->
            <th>#</th>
            <th>Ticket ID</th>
            <th>Title</th>
            <th>Urgency</th>
            <th>Created at</th>
            <th>Status</th>
            <th>File Attached</th>
            <th>Assigned To</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
// Include your database connection settings
include 'include.php'; // Make sure this file sets up $conn

// Prepare the SQL statement to fetch records where assigned_to is either not NULL or NULL
$sql = "
    SELECT log_tickets.*, employee_access.username 
    FROM log_tickets 
    LEFT JOIN employee_access ON employee_access.id = log_tickets.assigned_to
    WHERE log_tickets.assigned_to IS NOT NULL 
       OR log_tickets.assigned_to IS NULL";

// Execute the query
$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    // Output data for each row
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td><input type="checkbox" name="ticket_ids[]" value="' . htmlspecialchars($row['ticket_id']) . '"></td>'; // Add checkbox
        echo '<td>' . htmlspecialchars($row['ticket_id']) . '</td>';
        echo '<td>' . htmlspecialchars($row['title']) . '</td>';
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

        // Display username or show "Unassigned" if NULL
        $username = !empty($row['username']) ? htmlspecialchars($row['username']) : 'Unassigned';
        echo '<td>' . $username . '</td>';

        // Action buttons
        echo '<td>';
        echo '<a href="view_ticket.php?id=' . htmlspecialchars($row['ticket_id']) . '" class="view-btn">View</a> | ';
        echo '<a href="delete_ticket.php?id=' . htmlspecialchars($row['ticket_id']) . '" class="delete-btn" onclick="return confirm(\'Are you sure you want to delete this ticket?\');">Delete</a>';
        echo '</td>';

        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="8">No tickets found</td></tr>'; // Adjust colspan to match the number of columns in your table
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
    document.querySelector('.assign-btn').addEventListener('click', function() {
    // Get the selected checkboxes
    const selectedTicketIds = Array.from(document.querySelectorAll('input[name="ticket_ids[]"]:checked'))
                                   .map(checkbox => checkbox.value);

    // Get the assigned user from the input field
    const assignee = document.getElementById('assignInput').value;

    // Check if at least one ticket is selected and assignee is provided
    if (selectedTicketIds.length === 0) {
        Swal.fire({
    icon: 'warning',
    title: 'Oops...',
    text: 'Please select at least one ticket.',
    confirmButtonText: 'OK'
});

        return;
    }
    if (!assignee) {
       Swal.fire({
    icon: 'warning',
    title: 'Oops...',
    text: 'Please Inter an Assignee',
    confirmButtonText: 'OK'
});

        return;
    }

    // Send data to the server via AJAX
    fetch('assign_tickets.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            ticket_ids: selectedTicketIds,
            assign_to: assignee
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
Swal.fire({
    icon: 'success',
    title: 'KCBL Tickets',
    text: 'Tickets assigned successfully!',
    confirmButtonText: 'OK'
}).then((result) => {
    if (result.isConfirmed) {
        window.location.reload(); // Refresh the page
    }
});


        } else {
            alert("Failed to assign tickets: " + data.message);
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("An error occurred. Please try again.");
    });
});

</script>
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
<!-- JavaScript to handle fethed names -->
<script>
// scripts.js
function fetchNames() {
    const input = document.getElementById('assignInput');
    const suggestionsDiv = document.getElementById('suggestions');
    const assignUserIdInput = document.getElementById('assignUserId'); // Get the user ID input field

    // Clear previous suggestions
    suggestionsDiv.innerHTML = '';

    // Fetch names from the server
    fetch('fetch_ICT_names.php')
        .then(response => response.json())
        .then(data => {
            // Loop through the fetched data and create suggestion items
            data.forEach(item => {
                const suggestionItem = document.createElement('div');
                suggestionItem.textContent = `${item.name} (ID: ${item.id})`; // Show name with its ID
                suggestionItem.classList.add('suggestion-item');
                suggestionItem.dataset.userId = item.id; // Store the user ID in a data attribute

                // When the suggestion is clicked, set the input value and user ID
                suggestionItem.addEventListener('click', () => {
                    input.value = item.id;
                    assignUserIdInput.value = item.id; // Update the user ID field
                    suggestionsDiv.innerHTML = ''; // Clear suggestions after selection
                });

                // Append the suggestion item to the suggestions div
                suggestionsDiv.appendChild(suggestionItem);
            });
        })
        .catch(error => console.error('Error fetching names:', error));
}

// Hide the suggestions when the input field loses focus
document.getElementById('assignInput').addEventListener('blur', () => {
    setTimeout(() => {
        document.getElementById('suggestions').innerHTML = ''; // Clear suggestions when input loses focus
    }, 100); // Delay to allow click events on suggestions to be registered
});

// Show suggestions as the user types
document.getElementById('assignInput').addEventListener('input', fetchNames);

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
