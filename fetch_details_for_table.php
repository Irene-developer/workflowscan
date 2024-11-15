<?php
include 'include.php';

if (isset($_GET['table'])) {
    $table = $_GET['table'];

    // Ensure the table name is safe to use
    $allowedTables = [
        'imprest_safari' => ['imprest_id', 'username', 'status', 'Days', 'retirement_status'],
        'imprest_expenditure' => ['imprest_id', 'username', 'status', 'Approver1', 'Approver2'],
        'incidents' => ['id', 'name', 'reporting_date', 'incident_date', 'status'],
        'memos' => ['id', 'username', 'date', 'classfication', 'status', 'subject'],
        'retirement' => ['id', 'applicant_name', 'nature_of_claim', 'date', 'retirement_status']
    ]; // Add the actual column names for each table

    if (array_key_exists($table, $allowedTables)) {
        $columns = implode(', ', $allowedTables[$table]);
        $sql = "SELECT $columns FROM $table";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $rows = [];
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            echo json_encode([
                'columns' => $allowedTables[$table],
                'rows' => $rows
            ]);
        } else {
            echo json_encode(['columns' => $allowedTables[$table], 'rows' => []]);
        }
    } else {
        echo json_encode(['error' => 'Invalid table name']);
    }
} else {
    echo json_encode(['error' => 'No table specified']);
}

$conn->close();
?>
