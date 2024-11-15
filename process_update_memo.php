<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="x-icon" href="KCBLLOGO.PNG">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Memo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>
<?php
session_start();
include 'include.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $memo_id = $_POST['id'];
    $username = $_POST['username'];
    $date = $_POST['date'];
    $departmentName = $_POST['departmentName'];
    $refNo = $_POST['refNo'];
    $classification = $_POST['classfication'];
    $to = $_POST['To'];
    $from = $_POST['from'];
    $subject = $_POST['subject'];
    $content = $_POST['content'];
    //$signature_url = $_POST['signature_[]'];

    // Prepare the SQL update query
    $sql = "UPDATE memos SET username=?, date=?, departmentName=?, refNo=?, classfication=?, `to`=?, `from`=?, subject=?, content=? WHERE id=?";
    $stmt = $conn->prepare($sql);

    // Check if prepare() succeeded
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind parameters to the statement
    $stmt->bind_param("sssssssssi", $username, $date, $departmentName, $refNo, $classification, $to, $from, $subject, $content, $memo_id);

    // Execute the update statement
    if ($stmt->execute()) {
        // Update "through" fields dynamically
        for ($i = 1; $i <= 10; $i++) {
            $throughVar = "through" . ($i == 1 ? "" : $i);
            if (isset($_POST[$throughVar]) && !empty($_POST[$throughVar])) {
                $updateThroughSql = "UPDATE memos SET $throughVar=? WHERE id=?";
                $stmtThrough = $conn->prepare($updateThroughSql);
                if (!$stmtThrough) {
                    die("Error preparing 'through' statement: " . $conn->error);
                }
                $stmtThrough->bind_param("si", $_POST[$throughVar], $memo_id);
                if (!$stmtThrough->execute()) {
                    die("Error updating 'through' field $throughVar: " . $stmtThrough->error);
                }
                $stmtThrough->close();
            }
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();

        // Show SweetAlert success message and redirect to dashboard
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Update Successful',
                    text: 'Memo updated successfully.',
                    confirmButtonText: 'OK'
                }).then(function() {
                    window.location = 'dashboard.php';
                });
              </script>";
        exit;
    } else {
        echo "Error updating memo: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Handle invalid request method
    echo "Invalid request method!";
}
?>
</body>
</html>
