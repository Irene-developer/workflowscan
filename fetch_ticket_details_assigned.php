<?php
// Include your database connection settings
include 'include.php'; // Make sure this file sets up $conn

// Get ticket ID from query parameters
$ticketId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($ticketId > 0) {
    // Query to fetch ticket details
    $sql = "SELECT * FROM log_tickets WHERE ticket_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $ticketId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if a result is returned
    if ($result->num_rows > 0) {
        $ticket = $result->fetch_assoc();
        
        // Output the ticket details using echo
        // Ticket info in table format
        echo '<table style="width: 100%; border-collapse: collapse; text-align: center;">';
        echo '<tr>';
        echo '<th>From</th>';
        echo '<th>Ticket ID</th>';
        echo '<th>Category</th>';
        echo '<th>Urgency</th>';
        echo '<th>Created At</th>';
        echo '<th>Status</th>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>' . htmlspecialchars($ticket['username']) . '</td>';
        echo '<td>' . htmlspecialchars($ticket['ticket_id']) . '</td>';
        echo '<td>' . htmlspecialchars($ticket['category']) . '</td>';
        echo '<td>' . htmlspecialchars($ticket['urgency']) . '</td>';
        echo '<td>' . htmlspecialchars($ticket['created_at']) . '</td>';
        echo '<td>' . htmlspecialchars($ticket['status']) . '</td>';
        echo '</tr>';
        echo '</table>';

        // Title section
        echo '<div class="title-section" style="margin-top: 20px; text-align: center;">';
        echo '<h3 style="font-weight: bold;">Title:</h3>';
        echo '<p style="font-size: 18px; background-color: #f1f1f1; padding: 10px; border-radius: 5px;">' . htmlspecialchars($ticket['title']) . '</p>';
        echo '</div>';

        // Description section
        echo '<div class="description-section" style="margin-top: 20px; text-align: center;">';
        echo '<h3 style="font-weight: bold;">Description:</h3>';
        echo '<p style="font-size: 18px; background-color: #f1f1f1; padding: 10px; border-radius: 5px;">' . htmlspecialchars($ticket['description']) . '</p>';
        echo '</div>';

        // Status update form
        echo '<div class="status-update-section" style="margin-top: 20px; text-align: center;">';
        echo '<h3 style="font-weight: bold;">Update Status:</h3>';
        echo '<form action="update_ticket_status.php" method="post">';
        echo '<input type="hidden" name="ticket_id" value="' . htmlspecialchars($ticket['ticket_id']) . '">';
        echo '<select name="status" required>';
        echo '<option value="Open" ' . ($ticket['status'] == 'Open' ? 'selected' : '') . '>Open</option>';
        echo '<option value="In Progress" ' . ($ticket['status'] == 'In Progress' ? 'selected' : '') . '>In Progress</option>';
        echo '<option value="Resolved" ' . ($ticket['status'] == 'Resolved' ? 'selected' : '') . '>Resolved</option>';
        echo '<option value="Closed" ' . ($ticket['status'] == 'Closed' ? 'selected' : '') . '>Closed</option>';
        echo '</select>';
        echo '<button type="submit">Update Status</button>';
        echo '</form>';
        echo '</div>';

    } else {
        echo '<p>No details found for the specified ticket.</p>';
    }

    $stmt->close();
} else {
    echo '<p>Invalid ticket ID.</p>';
}

$conn->close();
?>
