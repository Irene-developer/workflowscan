<?php
// Include your database connection settings
include 'include.php'; // Ensure this file sets up $conn

// Check if the id parameter is set
if (isset($_GET['id'])) {
    $ticket_id = intval($_GET['id']);

    // Prepare the SQL statement
    $sql = "DELETE FROM log_tickets WHERE ticket_id = ?";
    $stmt = $conn->prepare($sql);
    
    // Bind the parameter and execute the statement
    if ($stmt) {
        $stmt->bind_param('i', $ticket_id);
        $stmt->execute();

        // Check if the delete was successful
        if ($stmt->affected_rows > 0) {
            // Redirect with a success message
            header('Location: log_ticket.php?message=Ticket deleted successfully');
            exit();
        } else {
            // Redirect with an error message
            header('Location: log_ticket.php?message=Ticket could not be deleted');
            exit();
        }
        
        // Close the statement
        $stmt->close();
    } else {
        // Redirect with an error message
        header('Location: log_ticket.php?message=Error preparing statement');
        exit();
    }
}

// Close the connection
$conn->close();
?>
