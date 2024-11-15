<?php
include 'include.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $departmentName = $_POST['departmentName'];
    $subDepartmentNames = $_POST['subdepartmentName'];
    // $headOfSubDepartments = $_POST['headOfsubDepartment'];

    if (!empty($departmentName) && !empty($subDepartmentNames)) {
        // Assuming $subDepartmentNames and $headOfSubDepartments have the same length
        $numSubDepartments = count($subDepartmentNames);
        $allInserted = true;
        $conn->begin_transaction();

        for ($i = 0; $i < $numSubDepartments; $i++) {
            $subDepartmentName = $subDepartmentNames[$i];
            // $headOfSubDepartment = $headOfSubDepartments[$i];

            // Use prepared statements to prevent SQL injection
            $stmt = $conn->prepare("INSERT INTO department (department_name, sub_department) VALUES (?, ?)");
            $stmt->bind_param("ss", $departmentName, $subDepartmentName);

            if (!$stmt->execute()) {
                $allInserted = false;
                $errorMessage = "Error: " . $stmt->error;
                break;
            }
        }

        if ($allInserted) {
            $conn->commit();
            $response = [
                'icon' => 'success',
                'title' => 'New Sub-Departments Created successfully',
                'message' => '',
                'showConfirmButton' => false,
                'timer' => 1500,
                'redirect' => 'department.php'
            ];
        } else {
            $conn->rollback();
            $response = [
                'icon' => 'error',
                'title' => 'Error creating sub-departments',
                'message' => $errorMessage,
                'showConfirmButton' => true
            ];
        }

        $stmt->close();
    } else {
        $response = [
            'icon' => 'error',
            'title' => 'All fields are required',
            'message' => '',
            'showConfirmButton' => true
        ];
    }

    $conn->close();
    echo json_encode($response);
    exit;
}
?>
