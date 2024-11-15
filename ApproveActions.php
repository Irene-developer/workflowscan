<?php
// Start the session
session_start();

if (isset($_POST['approve']) || isset($_POST['decline'])) {
    // Include database connection
    include 'include.php';

    $id = $_POST['id'];
    $comment = $_POST['comment'];
    $Position_name = $_SESSION['Position_name'];

    // Determine approver and action
    $action = isset($_POST['approve']) ? 1 : 0;
    $commentColumn = '';
    $approvalColumn = '';
    
    // Fetch the current ApproveActions and determine the appropriate comment column
    $sql = "SELECT Approver1, Approver2, ApproveActions FROM retirement WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if ($row['Approver1'] == $Position_name) {
        $commentColumn = 'comment1';
        $approvalColumn = 'ApproveActions';
    } elseif ($row['Approver2'] == $Position_name) {
        $commentColumn = 'comment2';
        $approvalColumn = 'ApproveActions';
    }

    // Update the retirement record with the approval action and comment
    if ($commentColumn) {
        $newApproveActions = ($action == 1 && $row['ApproveActions'] == 3) ? 1 : (($action == 1 && $row['ApproveActions'] == 1) ? 2 : 0);

        $sql = "UPDATE retirement SET $commentColumn = ?, $approvalColumn = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $comment, $newApproveActions, $id);
        $stmt->execute();
        $stmt->close();
    }

    // Redirect or display a message
    header("Location: approval_page.php");
    exit();
}

// Rest of your code to display memos and handle session
?>
