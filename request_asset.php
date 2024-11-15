<?php
// Start the PHP session
session_start();

// Include the database connection
include 'include.php';

// Check if the username is set in the session
if (isset($_SESSION['username']) && isset($_SESSION['id'])) {
    // If username is set, retrieve and display it
    $username = $_SESSION['username'];
    $id = $_SESSION['id'];
} else {
    echo "User not logged in.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="shortcut icon" type="x-icon" href="KCBLLOGO.PNG">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ICT Asset Request Form</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
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
    font-size: 28px;
    margin-bottom: 20px;
}
form {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
    max-width: 900px;
    margin: 0 auto;
}
label {
    font-weight: bold;
    color: #333;
}
input[type="text"],
input[type="date"],
select,
textarea {
    width: 100%;
    padding: 12px;
    margin-top: 5px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    box-sizing: border-box;
    font-size: 16px;
}
input[type="submit"] {
    background-color: #4CAF50;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    width: 100%;
    font-size: 16px;
    transition: background-color 0.3s ease, transform 0.3s ease;
}
input[type="submit"]:hover {
    background-color: #45a049;
    transform: scale(1.02);
}
.dynamic-field {
    display: none;
}
.show {
    display: block;
}
.clickable-btn {
    background-color: #3385ff;
    color: white;
    padding: 12px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    border-radius: 6px;
    max-width: 120px;
    margin-left: 990px;
    margin-bottom: 5px;
    transition: background-color 0.3s ease, transform 0.3s ease;
    font-size: 16px;
}
.clickable-btn:hover {
    background-color: #2874a6;
    transform: scale(1.05);
}
.popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    justify-content: center;
    align-items: center;
}
.popup-content {
    background: white;
    padding: 25px;
    border-radius: 8px;
    position: relative;
    max-width: 92%;
    width: 92%;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
    animation: fadeIn 0.5s ease-in;
}
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
  .close-btn {
        position: absolute;
        top: 50px;
        right: 10px;
        font-size: 24px;
        cursor: pointer;
    }
.popup-content table {
    width: 100%;
    border-collapse: collapse;
}
.popup-content th, .popup-content td {
    padding: 12px;
    text-align: left;
    border: 1px solid #ddd;
}
.popup-content th {
    background-color: #3385ff;
    color: white;
}
.popup-content tr:nth-child(even) {
    background-color: #f9f9f9;
}
.popup-content tr:hover {
    background-color: #f1f1f1;
}
.popup-content td.empty {
    background-color: #f8d7da; /* Light red */
    color: #721c24; /* Dark red text */
}
.popup-content td.non-empty {
    background-color: #d4edda; /* Light green */
    color: #155724; /* Dark green text */
}
</style>
</head>
<body>
<a href="#" class="clickable-btn" onclick="showPopup2(event, <?php echo $id; ?>)">Your Requests</a>
<!-- Popup container -->
<div id="popup" class="popup-overlay" style="display: none;">
    <div class="popup-content">
        <span class="close-btn" onclick="closePopup()">&times;</span>
        <h2>Asset Request Details</h2>
        <div id="popup-details">
            <!-- Details will be loaded here -->
        </div>
    </div>
</div>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <input type="hidden" id="employee_id" name="employee_id" value="<?php echo $id; ?>" required readonly><br>
    
    <label for="request_type">Request Type:</label><br>
    <select id="request_type" name="request_type" onchange="showRequestFields()" required>
        <option value="">Select Request Type</option>
        <option value="new">New</option>
        <option value="exchange">Exchange</option>
        <option value="shifting">Shifting</option>
        <option value="addition">Addition</option>
    </select><br>

    <div id="newFields" class="dynamic-field">
        <label for="new_asset_type">New Asset Type:</label><br>
        <input type="text" id="new_asset_type" name="new_asset_type"><br>
        <label for="new_asset_details">Asset Details:</label><br>
        <textarea id="new_asset_details" name="new_asset_details" rows="4"></textarea><br>
    </div>

    <div id="exchangeFields" class="dynamic-field">
        <label for="current_asset_tag">Current Asset Tag:</label><br>
        <input type="text" id="current_asset_tag" name="current_asset_tag"><br>
        <label for="new_asset_type_exchange">New Asset Type:</label><br>
        <input type="text" id="new_asset_type_exchange" name="new_asset_type_exchange"><br>
        <label for="new_asset_tag">New Asset Tag:</label><br>
        <input type="text" id="new_asset_tag" name="new_asset_tag"><br>
        <label for="exchange_reason">Reason for Exchange:</label><br>
        <textarea id="exchange_reason" name="exchange_reason" rows="4"></textarea><br>
    </div>

    <div id="shiftingFields" class="dynamic-field">
        <label for="new_asset_type_shift">New Asset Type:</label><br>
        <input type="text" id="new_asset_type_shift" name="new_asset_type_shift"><br>
        <label for="asset_tag_to_shift">Asset Tag to Shift:</label><br>
        <input type="text" id="asset_tag_to_shift" name="asset_tag_to_shift"><br>
        <label for="from_location">From Location:</label><br>
        <input type="text" id="from_location" name="from_location"><br>
        <label for="new_location">New Location:</label><br>
        <input type="text" id="new_location" name="new_location"><br>
    </div>

    <div id="additionFields" class="dynamic-field">
        <label for="new_asset_type_addition">New Asset Type:</label><br>
        <input type="text" id="new_asset_type_addition" name="new_asset_type_addition"><br>
        <label for="additional_info">Additional Information:</label><br>
        <textarea id="additional_info" name="additional_info" rows="4"></textarea><br>
    </div>

    <input type="submit" value="Submit">
</form>

<script>
function replaceEmptyCells() {
    // Select all table cells in the popup
    const cells = document.querySelectorAll('.popup-content td');

    cells.forEach(cell => {
        // Check if the cell is empty or contains only whitespace
        if (!cell.textContent.trim()) {
            cell.textContent = 'N/A';
            cell.classList.add('empty');
            cell.classList.remove('non-empty');
        } else {
            cell.classList.add('non-empty');
            cell.classList.remove('empty');
        }
    });
}
    
function showPopup2(event, employeeId) {
    event.preventDefault(); // Prevent the default anchor behavior

    // Show the popup
    document.getElementById('popup').style.display = 'flex';

    // Fetch and display details
    fetchDetails(employeeId);
}

function closePopup() {
    document.getElementById('popup').style.display = 'none';
}

function fetchDetails(employeeId) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'fetch_asset_details.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            document.getElementById('popup-details').innerHTML = xhr.responseText;
            replaceEmptyCells(); // Replace empty cells with "N/A" after details are loaded
        } else {
            document.getElementById('popup-details').innerHTML = 'Error fetching details.';
        }
    };
    xhr.send('employee_id=' + encodeURIComponent(employeeId));
}

function showRequestFields() {
    // Hide all dynamic fields
    document.querySelectorAll('.dynamic-field').forEach(field => field.classList.remove('show'));
    
    // Get the selected request type
    const requestType = document.getElementById('request_type').value;

    // Show fields based on the selected request type
    if (requestType === 'new') {
        document.getElementById('newFields').classList.add('show');
    } else if (requestType === 'exchange') {
        document.getElementById('exchangeFields').classList.add('show');
    } else if (requestType === 'shifting') {
        document.getElementById('shiftingFields').classList.add('show');
    } else if (requestType === 'addition') {
        document.getElementById('additionFields').classList.add('show');
    }
}
</script>


</body>
</html>

<?php
// PHP handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $employee_id = $_POST['employee_id'];
    $request_type = $_POST['request_type'];

    // Prepare SQL query based on request type
    if ($request_type == 'new') {
        $new_asset_type_new = $_POST['new_asset_type'];
        $new_asset_details = $_POST['new_asset_details'];
        $sql = "INSERT INTO asset_requests (employee_id, request_type, new_asset_type, new_asset_details) VALUES ('$employee_id', '$request_type', '$new_asset_type_new', '$new_asset_details')";
    } elseif ($request_type == 'exchange') {
        $current_asset_tag = $_POST['current_asset_tag'];
        $new_asset_type_exchange = $_POST['new_asset_type_exchange'];
        $new_asset_tag = $_POST['new_asset_tag'];
        $exchange_reason = $_POST['exchange_reason'];
        $sql = "INSERT INTO asset_requests (employee_id, request_type, current_asset_tag, new_asset_type, new_asset_tag, exchange_reason) VALUES ('$employee_id', '$request_type', '$current_asset_tag', '$new_asset_type_exchange', '$new_asset_tag', '$exchange_reason')";
    } elseif ($request_type == 'shifting') {
        $new_asset_type_shift = $_POST['new_asset_type_shift'];
        $asset_tag_to_shift = $_POST['asset_tag_to_shift'];
        $from_location = $_POST['from_location'];
        $new_location = $_POST['new_location'];
        $sql = "INSERT INTO asset_requests (employee_id, new_asset_type_shift, request_type, asset_tag_to_shift, from_location, new_location) VALUES ('$employee_id', '$request_type', '$new_asset_type_shift', '$asset_tag_to_shift', '$from_location', '$new_location')";
    } elseif ($request_type == 'addition') {
        $new_asset_type_addition = $_POST['new_asset_type_addition'];
        $additional_info = $_POST['additional_info'];
        $sql = "INSERT INTO asset_requests (employee_id, request_type, new_asset_type, additional_info) VALUES ('$employee_id', '$request_type', '$new_asset_type_addition', '$additional_info')";
    }

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: "Request submitted successfully."
                }).then(function() {
                    window.location.href = "dashboard.php";
                });
              </script>';
    } else {
        echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Failed to submit the request."
                });
              </script>';
    }
}
?>
