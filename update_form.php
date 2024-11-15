<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Ticket Status</title>
</head>
<body>
    <h1>Update Ticket Status</h1>
    <form action="update_ticket.php" method="POST">
        <label for="ticket_id">Ticket ID:</label>
        <input type="text" id="ticket_id" name="ticket_id" required>
        
        <label for="status">Status:</label>
        <select id="status" name="status">
            <option value="open">Open</option>
            <option value="closed">Closed</option>
        </select>
        
        <button type="submit">Update</button>
    </form>
</body>
</html>
