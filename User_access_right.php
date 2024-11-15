<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="x-icon" href="KCBLLOGO.PNG">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Access Rights</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        header {
            background-color: #fff;
            color: #fff;
            padding: 10px 0;
            text-align: center;
            display: flex;
            justify-content: space-between;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 1580px;
            margin: 0 auto;
        }

        header h1 {
            margin: 0;
            color: #3385ff;
        }

        .logo-section img {
            max-width: 100px;
            max-height: 50px;
            margin-left: 20px;
        }

        .container {
            max-width: 1500px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        footer {
            background-color: #fff;
            color: #3385ff;
            padding: 20px;
            text-align: center;
            margin-top: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        footer p {
            margin: 0;
        }

        /* Button Styles */
        .container-access {
            display: flex;
            align-items: center;
        }

        .container-access h2 {
            margin-right: auto;
        }

        .container-access button {
            background-color: #3385ff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px; /* Add some space between buttons if needed */
        }

        .container-access button:hover {
            background-color: #1e70bf;
        }
/* Button Styles */
        .add-button {
            background-color: #3385ff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            float: right;
            margin-bottom: 5px;
        }

        .add-button:hover {
            background-color: #1e70bf;
        }

        /* Popup Form Styles */
        .form-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fefefe;
            border: 1px solid #ccc;
            border-radius: 5px;
            z-index: 1000;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container input[type="text"] {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            box-sizing: border-box;
        }

        .form-container .btn {
            background-color: #3385ff;
            color: white;
            padding: 16px 20px;
            border: none;
            cursor: pointer;
            width: 100px;
            margin-bottom: 10px;
            opacity: 0.8;
            float: left;
            margin: 20px;
            border-radius: 1em;
            float: right;
        }

        .form-container .btn:hover {
            opacity: 1;
        }

        .form-container .cancel {
            background-color: #f44336;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
            background-color: #1e70bf;
        }
        
        /* Black background when form is open */
        .overlay {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background-color: rgba(0, 0, 0, 0.5); /* Black background with opacity */
            z-index: 999; /* Ensure this is above the form */
            display: none; /* Initially hidden */
        }

#positionNamesDropdown {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 150px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
    overflow-y: auto;
}

#departmentNamesDropdown {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 150px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
    overflow-y: auto;
}

.dropdown-item {
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

.dropdown-item:hover {
    background-color: #f1f1f1;
    cursor: pointer;
}
summary {
    cursor: pointer; /* Change cursor to pointer on hover */
}
/* Hide the dropdown content by default */
.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 150px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
}

/* Style the dropdown menu items */
.dropdown-content p {
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    color: black;
}

/* Show the dropdown content when summary is hovered */
.dropdown:hover .dropdown-content {
    display: block;
}
    </style>
</head>
<body>
<header>
    <div class="logo-section">
        <img src="KCBLLOGO.png" alt="Your Logo">
    </div>
    <h1>User Access Rights</h1>
    <div class="send-request">
        <a href="sendrequest.php"><i class='bx bx-add-to-queue bx-burst'></i></a>
    </div>
</header>

<div class="container">
    <div class="container-access"> 
        <h2>User Access Rights</h2>
        <button onclick="openForm()">Add new Access Rights</button>
    </div>
    
    <table>
        
        <tbody>
        <!-- Data will be dynamically populated here -->
      <?php
// Assuming you have a database connection established already
include 'include.php';

// Perform the query to fetch user access rights
$query = "SELECT * FROM user_access_rights";
$result = mysqli_query($conn, $query);

if ($result) {
    // Check if there are any rows returned
    if (mysqli_num_rows($result) > 0) {
        // Output table header
        echo "<table border='1'>
                <tr>
                    <th>User Access Rights ID</th>
                    <th>User Access Rights Name</th>
                    <th>Department ID</th>
                    <th>Position ID</th>
                    <th>Position Name</th>
                    <th>Department Name</th>
                </tr>";
        
        // Output data of each row
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['user_access_rights_id'] . "</td>";
            echo "<td>" . $row['user_access_rights_name'] . "</td>";
            echo "<td>" . $row['department_id'] . "</td>";
            echo "<td>" . $row['position_id'] . "</td>";
            echo "<td>" . $row['Position_name'] . "</td>";
            echo "<td>" . $row['department_name'] . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "No user access rights found";
    }
} else {
    echo "Error fetching user access rights: " . mysqli_error($conn);
}

// Close connection
mysqli_close($conn);
?>

        <!-- Add more rows as needed -->
        </tbody>
    </table>
</div>

<!-- The form to add a new position -->
<div id="positionForm" class="form-popup">

<?php
include 'include.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $departmentName = $_POST['departmentName'];
    $positionName = $_POST['positionName'];
    $accessName = $_POST['accessName'];
    // Check if both fields are not empty
    if (!empty($departmentName) && !empty($positionName) && !empty($accessName)) {
        // Check if department already exists
        $check_sql = "SELECT * FROM user_access_rights WHERE user_access_rights_name = '$accessName'";
        $result = $conn->query($check_sql);
        if ($result->num_rows > 0) {
            //echo "Department or Head of Department already exists!";
        } else {
            // Insert into department table
            $sql = "INSERT INTO user_access_rights (department_name, position_name, user_access_rights_name) VALUES ('$departmentName', '$positionName','$accessName')";
            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    } else {
        echo "Department Name and Position Name are required fields";
    }

    $conn->close();
}
?>
    <form action="" method="post" class="form-container">
        <h2>Add new Access Rights</h2>
        <label for="accessName"><b>Access Rights Name</b></label>
        <input type="text" placeholder="Enter New Access Rights Name" name="accessName" required>

        <label for="departmentName"><b>Department Name</b></label>
        <input type="text" placeholder="Enter Department Name" name="departmentName" required id="departmentNameInput">
        <div id="departmentNamesDropdown"></div>

        <label for="Position"><b>Position:</b></label>
        <input type="text" placeholder="Enter Position Name"  name="positionName" id="PositionNameInput"><br>
        <div id="positionNamesDropdown"></div>

        <button type="submit" class="btn">Add</button>
        <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
    </form>
</div>

<!-- Black background overlay -->
<div class="overlay" id="overlay" onclick="closeForm()"></div>

<script>
    function openForm() {
        document.getElementById("positionForm").style.display = "block";
        document.getElementById("overlay").style.display = "block"; // Show the overlay
    }

    function closeForm() {
        document.getElementById("positionForm").style.display = "none";
        document.getElementById("overlay").style.display = "none"; // Hide the overlay
    }
    //access for department and position
       //departmet name  
$(document).ready(function(){
    $('#departmentNameInput').click(function(){
        // Fetch department names using AJAX
        $.ajax({
            url: 'fetch_department_names.php',
            type: 'GET',
            success: function(response){
                // Parse JSON response
                var departmentNames = JSON.parse(response);
                // Display department names in a dropdown
                $('#departmentNamesDropdown').empty();
                departmentNames.forEach(function(department){
                    var departmentName = department.department_name;
                    var dropdownItem = $('<div class="dropdown-item">' + departmentName + '</div>');
                    dropdownItem.click(function(){
                        $('#departmentNameInput').val(departmentName);
                        $('#departmentNamesDropdown').empty();
                    });
                    $('#departmentNamesDropdown').append(dropdownItem);
                });
                $('#departmentNamesDropdown').slideDown();
            }
        });
    });
    
    $(document).click(function(e) {
        if (!$(e.target).is('#departmentNameInput') && !$(e.target).closest('#departmentNamesDropdown').length) {
            $('#departmentNamesDropdown').slideUp();
        }
    });
});

//position name
$(document).ready(function(){
    $('#PositionNameInput').click(function(){
        // Fetch position names using AJAX
        $.ajax({
            url: 'fetch_position_names.php',
            type: 'GET',
            success: function(response){
                // Parse JSON response
                var positionNames = JSON.parse(response);
                // Display position names in a dropdown
                $('#positionNamesDropdown').empty();
                positionNames.forEach(function(position){
                    var positionName = position.Position_name;
                    var dropdownItem = $('<div class="dropdown-item">' + positionName + '</div>');
                    dropdownItem.click(function(){
                        $('#PositionNameInput').val(positionName);
                        $('#positionNamesDropdown').empty();
                    });
                    $('#positionNamesDropdown').append(dropdownItem);
                });
                $('#positionNamesDropdown').slideDown();
            }
        });
    });
    
    $(document).click(function(e) {
        if (!$(e.target).is('#PositionNameInput') && !$(e.target).closest('#positionNamesDropdown').length) {
            $('#positionNamesDropdown').slideUp();
        }
    });
});
</script>
<?php include 'footer.php'; ?> 
</body>
</html>
