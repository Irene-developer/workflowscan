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
    <link rel="shortcut icon" type="x-icon" href="KCBLLOGO.PNG">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged Tickets Reports</title>
    <link rel="stylesheet" type="text/css" href="styles_css_log_ticket.css">
    <link rel="stylesheet" href="assets/fas fas fas 3/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/quill/quill.snow.css">
    <!-- Scripts -->
    <script src="assets/quill/quill.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="assets/js/jquery/jquery-3.7.1.min.js"></script>
    <script src="assets/sweetalert2/sweetalert2.all.min.js"></script>
    
    <script src="assets/chart/chart.js"></script>

    <style>
        #pieChartModal {
            display: none; /* Hidden by default */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 600px;
            position: relative;
        }
        .close-button {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            font-size: 18px;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
    <!-- Ticket Table Section -->
   <!-- Print Icon -->
    <i class="add-ticket-icon fas fa-print" onclick="openprint()"></i>

    <div class="ticket-table-container">
        <button onclick="window.location.href='dashboard.php'" class="back-btn">Back to Dashboard</button>
<form method="GET" action="">
    <label for="status">Filter by Status:</label>
    <select name="status" id="status">
        <option value="">All</option>




        <option value="Open">Open</option>
        <option value="In_Progress">In Progress</option>
        <option value="Resolved">Resolved</option>
        <option value="Closed">Closed</option>
    </select>

   <label for="assigned_to">Filter by Assigned To:</label>
    <input type="text" name="assigned_to" id="assigned_to" placeholder="Enter username" onclick="showDropdown()" readonly>

    <!-- Dropdown menu -->
    <div id="dropdown" class="dropdown">
        <ul id="dropdown-menu">
            <!-- Dropdown options will be populated by JavaScript -->
        </ul>
    </div>

    <input type="hidden" name="selected_id" id="selected_id"> <!-- Hidden field to store the ID -->
    <button type="submit">Filter</button>
    <!-- Button to trigger the modal -->
<button type="button" onclick="openPieChartModal()">Pie Chart</button>

</form>

<?php


// Retrieve session details
if (isset($_SESSION['department_name']) && isset($_SESSION['username'])) {
    $department_name = $_SESSION['department_name'];
    $username = $_SESSION['username'];
}

// Set the number of results per page
$resultsPerPage = 8;

// Initialize the conditions for filtering
$status = isset($_GET['status']) ? $_GET['status'] : '';
$assigned_to = isset($_GET['assigned_to']) ? $_GET['assigned_to'] : '';

// Base query for counting total records
$countSql = "SELECT COUNT(*) AS total FROM log_tickets WHERE 1=1";
$params = [];
$types = "";

// Add filtering by status if selected
if (!empty($status)) {
    $countSql .= " AND status = ?";
    $params[] = $status;
    $types .= "s";
}

// Add filtering by assigned_to if selected
if (!empty($assigned_to)) {
    $countSql .= " AND assigned_to = ?";
    $params[] = $assigned_to;
    $types .= "s";
}

// Prepare and execute the count query
$stmt = $conn->prepare($countSql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$totalResults = $result->fetch_assoc()['total'];
$totalPages = ceil($totalResults / $resultsPerPage);

// Determine the current page number
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($currentPage > $totalPages) $currentPage = $totalPages;
if ($currentPage < 1) $currentPage = 1;

// Calculate the offset for pagination
$offset = ($currentPage - 1) * $resultsPerPage;

// Query to fetch tickets with pagination and filters
$sql = "SELECT * FROM log_tickets WHERE 1=1";
$params = [];
$types = "";

// Add filtering by status if selected
if (!empty($status)) {
    $sql .= " AND status = ?";
    $params[] = $status;
    $types .= "s";
}

// Add filtering by assigned_to if selected
if (!empty($assigned_to)) {
    $sql .= " AND assigned_to = ?";
    $params[] = $assigned_to;
    $types .= "s";
}

// Append LIMIT and OFFSET to the query
$sql .= " LIMIT ? OFFSET ?";
$params[] = $resultsPerPage;
$params[] = $offset;
$types .= "ii";

// Prepare and execute the ticket fetch query
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>
<!-- Displaying the Table with Filtered Results -->
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
            <th>Assigned To</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
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
                echo '<td>';
                if (!empty($row['file_path'])) {
                    echo '<a href="#" data-file="' . htmlspecialchars($row['file_path']) . '" class="view-file-link">View File</a>';
                }
                echo '</td>';
                echo '<td>' . htmlspecialchars($row['assigned_to']) . '</td>';
                echo '<td>';
                echo '<a href="view_ticket.php?id=' . htmlspecialchars($row['ticket_id']) . '" class="view-btn">View</a> | ';
                echo '<a href="#" class="delete-btn" onclick="confirmDeletion(' . htmlspecialchars($row['ticket_id']) . '); return false;">Delete</a>';
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
<!-- View Ticket Popup -->
<div id="viewTicketPopup" class="myviewpopup">
    <div class="myviewpopup-content">
        <span class="close-myviewpopup">&times;</span>
        <div id="ticketDetails">
            <!-- Ticket details will be dynamically loaded here -->
        </div>
    </div>
</div>
<!-- Modal Structure -->
    <div id="pieChartModal">
        <div class="modal-content">
            <span class="close-button" onclick="closePieChartModal()">&times;</span>
            <canvas id="pieChartCanvas"></canvas>
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
<script>
    
    // Function to show the dropdown
function showDropdown() {
    var dropdown = document.getElementById('dropdown');
    dropdown.style.display = 'block'; // Show the dropdown
    
    // Fetch data from the server
    fetch('fetch_usernames_tickets.php')
        .then(response => response.json())
        .then(data => {
            var dropdownMenu = document.getElementById('dropdown-menu');
            dropdownMenu.innerHTML = ''; // Clear previous options

            data.forEach(item => {
                var listItem = document.createElement('li');
                listItem.innerHTML = `<a href="#" data-id="${item.id}">${item.username}</a>`;
                dropdownMenu.appendChild(listItem);
            });

            // Add click event listeners to the dropdown items
            dropdownMenu.querySelectorAll('a').forEach(anchor => {
                anchor.addEventListener('click', function(event) {
                    event.preventDefault();
                    var username = this.textContent;
                    var id = this.getAttribute('data-id');
                    document.getElementById('assigned_to').value = username;
                    document.getElementById('selected_id').value = id;
                    dropdown.style.display = 'none'; // Hide dropdown
                });
            });
        })
        .catch(error => console.error('Error fetching usernames:', error));
}

// Function to handle filter action
function filterResults() {
    var selectedId = document.getElementById('selected_id').value;
    // Use the selected ID to filter results
    console.log('Filtering by ID:', selectedId);

    // You can submit the form or make an AJAX request to filter results
    // Example: document.getElementById('filter-form').submit();
}

// Optional: Hide the dropdown when clicking outside of it
document.addEventListener('click', function(event) {
    var dropdown = document.getElementById('dropdown');
    var input = document.getElementById('assigned_to');
    if (!dropdown.contains(event.target) && event.target !== input) {
        dropdown.style.display = 'none'; // Hide the dropdown
    }
});


</script>
<script>
    
function openprint(ticketId) {
    window.open('generate_tickets_pdf.php?id=' + encodeURIComponent(ticketId), '_blank');
}

// Attach event listener to the print icon (make sure to pass the ticket ID)
document.querySelectorAll('.print-btn').forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault();
        const ticketId = this.getAttribute('data-id'); // Extract ticket ID from data-id attribute
        openprint(ticketId);
    });
});


</script>
    <script>
        function openPieChartModal() {
            document.getElementById('pieChartModal').style.display = 'flex';
            fetchChartData(); // Fetch data when the modal opens
        }

        function closePieChartModal() {
            document.getElementById('pieChartModal').style.display = 'none'; // Hide modal
        }

        function fetchChartData() {
            fetch('fetch_chart_data_2.php') // Ensure this endpoint is correct
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Data fetched:', data); // Log the data for debugging
                    renderPieChart(data);
                })
                .catch(error => {
                    console.error('Error fetching chart data:', error); // Log any errors
                });
        }

        function renderPieChart(data) {
            const ctx = document.getElementById('pieChartCanvas').getContext('2d');

            // Check if data has the expected format
            if (!data.labels || !data.values || data.labels.length !== data.values.length) {
                console.error('Invalid data format:', data);
                return;
            }

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: data.labels,
                    datasets: [{
                        data: data.values,
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': ' + tooltipItem.raw;
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
<script>
function confirmDeletion(ticketId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirect to the delete URL or perform an AJAX request to delete the ticket
            window.location.href = 'delete_ticket.php?id=' + ticketId;
        }
    });
}
</script>


    <?php include 'footer.php'; ?>
</body>
</html>
