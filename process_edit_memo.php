<?php

include 'include.php';

// Check if 'id' exists in $_GET and assign it to $memo_id
if (isset($_GET['id'])) {
    $memo_id = $_GET['id'];
} else {
    die("Error: 'id' parameter missing in the request.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize $memo with existing values from the database
    $stmt = $conn->prepare("SELECT * FROM memos WHERE id = ?");
    $stmt->bind_param("i", $memo_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $memo = $result->fetch_assoc();
    $stmt->close();

    if (!$memo) {
        die("Error: Memo not found.");
    }

    // Ensure 'signature_url' exists in $_POST
    $signature_url = isset($_POST['signature_url']) ? $_POST['signature_url'] : '';

    $fields = [
        'username' => $_POST['username'],
        'date' => $_POST['date'],
        'departmentName' => $_POST['departmentName'],
        'refNo' => $_POST['refNo'],
        'classfication' => $_POST['classfication'],
        'to' => $_POST['To'],
        'from' => $_POST['from'],
        'subject' => $_POST['subject'],
        'content' => $_POST['content'],
        'signature_path' => $signature_url
    ];

    for ($i = 1; $i <= 10; $i++) {
        $throughVar = "through" . ($i == 1 ? "" : $i);
        if (isset($_POST[$throughVar]) && !empty($_POST[$throughVar])) {
            $fields[$throughVar] = $_POST[$throughVar];
        }
    }

    $changedFields = [];
    foreach ($fields as $key => $value) {
        if ($value != $memo[$key]) {
            $changedFields[$key] = $value;
        }
    }

    if (!empty($changedFields)) {
        $setString = implode(", ", array_map(fn($key) => "$key = ?", array_keys($changedFields)));
        $sql = "UPDATE memos SET $setString WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $types = str_repeat("s", count($changedFields)) . "i";
            $values = array_values($changedFields);
            $values[] = $memo_id;

            $stmt->bind_param($types, ...$values);

            if ($stmt->execute()) {
                header("Location: dashboard.php");
                exit;
            } else {
                echo "Error updating memo: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    } else {
        echo "No changes detected!";
    }
}

?>
