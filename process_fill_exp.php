<?php
include 'include.php';
// Start the PHP session
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_SESSION['username'];
    $department_name = $_SESSION['department_name'];
    $position_name = $_SESSION['Position_name'];
    $imprest_amount = $_POST['requested_amount'];
    $branch_name = $_POST['switched_branch'];
    $signature_path = ''; // Handle signature path separately
    $imprest_purpose = $_POST['purpose'];
    $date = $_POST['date'];
    $outstanding_imprest_amount = 0; // You may need to calculate this based on existing data

    // Insert data into the database
    $sql = "INSERT INTO imprest_expenditure (username, position_name, department_name, imprest_amount, branch_name, signature_path, imprest_purpose, date, outstanding_imprest_amount) VALUES ('$username', '$position_name', '$department_name', $imprest_amount, '$branch_name', '$signature_path', '$imprest_purpose', '$date', $outstanding_imprest_amount)";

    // Check if the insertion was successful
    if (mysqli_query($conn, $sql)) {
        echo "<script>
    Swal.fire({
        icon: 'success',
        title: 'Record inserted successfully',
        showConfirmButton: false,
        timer: 1500
    }).then(function () {
        window.location.href = 'fill_exp.php';
    });
</script>";


    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>
