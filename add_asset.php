<?php
include 'include.php';

session_start();


// Check if the department_name and Position_name are set in session
if (isset($_SESSION['department_name']) && isset($_SESSION['Position_name']) || isset($_SESSION['department_name'])) {
    // Retrieve department_name and Position_name from session
    $department_name = $_SESSION['department_name'];
    $Position_name = $_SESSION['Position_name'];

}

 ?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="shortcut icon" type="x-icon" href="KCBLLOGO.PNG">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ICT Asset Management</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f2f2f2;
        margin: 0;
        padding: 20px;
    }
    h2 {
        text-align: center;
        color: #333;
    }
    form {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        max-width: 900px;
        margin: 0 auto;
    }
    label {
        font-weight: bold;
    }
    input[type="text"],
    input[type="date"],
    select,
    textarea {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }
    input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
    }
    input[type="submit"]:hover {
        background-color: #45a049;
    }
    .as-field {
        display: none;
    }
    label[for="assigned_to"] {
    font-weight: bold;
    display: block;
    margin-bottom: 5px;
}

#assigned_to {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

#employee_names {
    width: 30%;
    padding: 10px;
    margin-top: 5px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    display: none; /* Initially hidden, shown when data is fetched */
    background-color: #fff;
    position: absolute;
    z-index: 1000;
    max-height: 150px;
    overflow-y: auto;
}

#employee_names option {
    padding: 10px;
    cursor: pointer;
}

#employee_names option:hover {
    background-color: #f2f2f2;
}

</style>
</head>
<body>

<h2>ICT Asset Management</h2>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="asset_name">Asset Name:</label><br>
    <input type="text" id="asset_name" name="asset_name" required><br>

    <label for="asset_type">Asset Type:</label><br>
    <select id="asset_type" name="asset_type" required>
        <option value="laptop">Laptop</option>
        <option value="desktop">Desktop</option>
        <option value="printer">Printer</option>
        <option value="scanner">Scanner</option>
        <option value="network_equipment">Network Equipment</option>
    </select><br>

    <label for="asset_tag">Asset Tag:</label><br>
    <input type="text" id="asset_tag" name="asset_tag" required><br>

    <label for="serial_number">Serial Number:</label><br>
    <input type="text" id="serial_number" name="serial_number" required><br>

    <label for="purchase_date">Purchase Date:</label><br>
    <input type="date" id="purchase_date" name="purchase_date" required><br>

    <label for="warranty_expiry">Warranty Expiry:</label><br>
    <input type="date" id="warranty_expiry" name="warranty_expiry"><br>

    <label for="status">Status:</label><br>
    <select id="status" name="status">
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
        <option value="retired">Retired</option>
    </select><br>

    <label for="assigned_to">Assigned To (Employee ID):</label><br>
    <input type="text" id="assigned_to" name="assigned_to" onfocus="fetchEmployeeNames()" onclick="fetchEmployeeNames()"><br>
    <select id="employee_names" name="employee_names" class="as-field" size="5" onclick="setEmployeeId(this)"></select><br>

    <label for="department">Department:</label><br>
    <input type="text" id="department" name="department" value="<?php echo $department_name ?>"><br>

    <label for="location">Location:</label><br>
    <input type="text" id="location" name="location"><br>

    <label for="remarks">Remarks:</label><br>
    <textarea id="remarks" name="remarks" rows="4" cols="50"></textarea><br>

    <input type="submit" value="Submit">
</form>

<script>
    // Function to fetch employee names
    function fetchEmployeeNames() {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "fetch_employee_names_for_asset.php", true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);
                var employeeNamesSelect = document.getElementById("employee_names");
                employeeNamesSelect.innerHTML = "";
                response.forEach(function(employee) {
                    var option = document.createElement("option");
                    option.value = employee.id;
                    option.textContent = employee.id + " - " + employee.first_name + " " + employee.last_name;
                    employeeNamesSelect.appendChild(option);
                });
                employeeNamesSelect.style.display = "block";
            }
        };
        xhr.send();
    }

    // Function to set the employee ID to the assigned_to input field
    function setEmployeeId(selectElement) {
        document.getElementById("assigned_to").value = selectElement.value;
        selectElement.style.display = "none";
    }

    // Get current date
    var today = new Date().toISOString().slice(0, 10);
    // Set today's date as the default value for the purchase date input field
    document.getElementById("purchase_date").value = today;
</script>

<?php
include 'include.php';

// Process form submission if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $asset_name = $_POST["asset_name"];
    $asset_type = $_POST["asset_type"];
    $asset_tag = $_POST["asset_tag"];
    $serial_number = $_POST["serial_number"];
    $purchase_date = $_POST["purchase_date"];
    $warranty_expiry = $_POST["warranty_expiry"];
    $status = $_POST["status"];
    $assigned_to = $_POST["assigned_to"];
    $department = $_POST["department"];
    $location = $_POST["location"];
    $remarks = $_POST["remarks"];
    
    // Insert data into the database
    $sql = "INSERT INTO ict_assets (asset_name, asset_type, asset_tag, serial_number, purchase_date, warranty_expiry, status, assigned_to, department, location, remarks)
    VALUES ('$asset_name', '$asset_type', '$asset_tag', '$serial_number', '$purchase_date', '$warranty_expiry', '$status', '$assigned_to', '$department', '$location', '$remarks')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Asset added successfully.'
                    }).then(function() {
                        window.location = 'dashboard.php'; // Redirect to a new page if needed
                    });
                 </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
</body>
</html>
