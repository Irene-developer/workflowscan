<?php
// Start the PHP session
session_start();

// Check if the username is set in the session
if(isset($_SESSION['username']) && isset($_SESSION['department_name']) && isset($_SESSION['Position_name'])) {
    // If username is set, retrieve and display it
    $username = $_SESSION['username'];
    $department_name=$_SESSION['department_name'];
    $position_name=$_SESSION['Position_name'];
    
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="x-icon" href="KCBLLOGO.PNG">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Memo</title>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet" type="text/css" href="stylescreatememo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        /* Styles for the popup */
        .popup-overlay {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .popup {
            display: none;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            border: 1px solid #ccc;
            background-color: #fff;
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .popup.show, .popup-overlay.show {
            display: block;
            opacity: 1;
        }
        .popup-content {
            max-height: 300px;
            overflow-y: auto;
        }
        .popup button {
            margin-top: 10px;
        }
         .selectbutton {
    background-color: #4CAF50; /* Green */
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 8px;
    transition-duration: 0.4s;
}

.selectbutton:hover {
    background-color: #45a049; /* Darker Green */
}
#submit-btn {
    background-color: #007bff; /* Blue */
    border: none;
    color: white;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 5px;
    transition-duration: 0.4s;
}

#submit-btn:hover {
    background-color: #0056b3; /* Darker Blue */
}

#submit-btn .fa {
    margin-right: 5px; /* Adjust icon spacing */
}


    </style>
</head>
<body>
    <?php
// Start the PHP session
//session_start();
// Save the data to the database
include 'include.php'; // Include your database connection file

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $date = $_POST['date'];
    $departmentName = $_POST['departmentName'];
    $refNo = $_POST['refNo'];
    $classification = $_POST['classfication']; // corrected column name
    $to = $_POST['To'];
    $through = $_POST['through'];
    $from = $_POST['from'];
    $subject = $_POST['subject'];
    $content = $_POST['content']; // Assuming you have an element with the name "content" in your form

    // Example query to insert data into a table named "memos"
    $sql = "INSERT INTO memos (username, date, departmentName, refNo, classfication, `to`, `through`, `from`, subject, content) 
            VALUES ('$username', '$date', '$departmentName', '$refNo', '$classification', '$to', '$through', '$from', '$subject', '$content')";

    if ($conn->query($sql) === TRUE) {
        // Show a toast message
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Memo Created successfully',
                showConfirmButton: false,
                timer: 1500
            }).then(function () {
                window.location.href = 'create_memo.php';
            });
        </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    // Redirect to the form page if accessed directly without form submission
    // This part is removed as we want to stay on the same page after submission
    // You can add code here to handle direct access to this script if needed
}
?>

    <div class="container">
        <h1 style="border-bottom: solid; border-color: black;">CREATE MEMO</h1>
        <form action="" method="POST" class="form-show">
            <div style="display: flex; justify-content: space-between; padding: 10px; margin-bottom: 15px; border-bottom: solid; border-color: black; align-content: center;">
                 <img src="KCBLLOGO.png" style="max-width: 300px; align-items: center; border-right: solid; border-color: black; margin-left: 100px;">
                <h1 style="margin-right: 100px; margin-top: 60px">Internal Memo</h1 style="margin-right: 300px;">

            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: solid; border-color: black;">
               
            <div class="form-header">
            <label for="username">Name</label>
            <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
            </div>


            <div class="form-header">
                <label for="date">Date</label>
                <input type="text" id="date" name="date" required>
            </div>

            <div class="form-header">
                <label for="departmentName">Department</label>
                <input type="text" id="departmentName" name="departmentName" value="<?php echo $department_name; ?>" required>
            </div>

            </div></br>

           <div style="display: flex; justify-content: space-between; border-bottom: solid; border-color: black; margin-top: 17px;">
    <div class="form-header">
        <?php
        include 'include.php';
        
function generateRefNo($length = 10) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()-_=+';
    $refNo = '';
    $maxIndex = strlen($characters) - 1;
    for ($i = 0; $i < $length; $i++) {
        $refNo .= $characters[rand(0, $maxIndex)];
    }
    return $refNo;
}
?>

    <label for="refNo">RefNo:</label>
    <input type="text" id="refNo" name="refNo" value="<?php echo generateRefNo(); ?>" required>
</div>


    <div class="form-header">
        <label for="classfication">Classfication</label>
        <select id="classfication" name="classfication" required>
            <option value="">Select origin</option>
            <option value="Internal Memo">Internal Memo</option>
            <option value="Open">Open</option>
            <option value="Confidential">Confidential</option>
            <!-- Add more options as needed -->
        </select>
    </div>
</div>

<div style="display: flex; justify-content: space-between; border-bottom: solid; border-color: black; margin-top: 17px;">

    <div class="form-header" style="margin-left: 13px;">
        <label for="To">To</label>
        <select id="To" name="To" required>
            <option value="">Select Position</option>
            <?php
include 'include.php';

// Query to fetch unique position names from the database
$sql = "SELECT DISTINCT position_name FROM position";
$result = $conn->query($sql);

// Populate dropdown with fetched data
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<option value='" . $row["position_name"] . "'>" . $row["position_name"] . "</option>";
    }
} else {
    echo "<option value=''>No positions found</option>";
}
$conn->close();
?>

        </select>
    </div>


    <div class="form-header">
    <button id="show-popup" class="selectbutton">Select Positions</button>

    <div class="popup-overlay" id="popup-overlay"></div>
    <div class="popup" id="popup">
        <div class="popup-content">
            <form id="position-form">
                <?php
include 'include.php';

// Start the PHP session
//session_start();

// Check if the position name is set in the session
if (isset($_SESSION['Position_name'])) {
    // If position name is set, retrieve and store it
    $currentPositionName = $_SESSION['Position_name'];
} else {
    $currentPositionName = ''; // Default to empty string if session variable is not set
}

// Query to fetch unique position names from the database, excluding the current position name
$sql = "SELECT DISTINCT position_name FROM position";

// Exclude the current position name from the query result
if (!empty($currentPositionName)) {
    $sql .= " WHERE position_name != '$currentPositionName'";
}

$result = $conn->query($sql);

// Populate popup with fetched data
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<label><input type='checkbox' name='positions[]' value='" . $row["position_name"] . "'> " . $row["position_name"] . "</label><br>";
    }
} else {
    echo "No positions found";
}
$conn->close();
?>

                <button type="button" id="submit-btn"><li class="fa fa-thumb-tack" ></li></button>
            </form>
        </div>
    </div>
</div>

 


    <div class="form-header">
        <label for="from">From</label>
         <?php

include 'include.php';

// Query to fetch unique position names from the database
$sql = "SELECT DISTINCT position_name FROM position";
$result = $conn->query($sql);

$conn->close();
?>

        <input type="text" id="from" name="from" value="<?php echo $position_name; ?>" required readonly >
    </div>
   
</div>


     <label for="subject">Subject:</label>
<input type="text" id="subject" name="subject" required oninput="capitalizeAndBold(this)">



            <label for="content">Content:</label><br>
<textarea id="editor" name="content" style="max-width: 100%; height: 300px;"></textarea>
<br><br>


           

            <input type="submit" value="Create Memo">
        </form>
    </div>
<script>
    function capitalizeAndBold(input) {
        // Convert input value to uppercase
        input.value = input.value.toUpperCase();
        
        // Set font weight to bold
        input.style.fontWeight = 'bold';
    }
</script>
    <script>
        // Function to get the current date in the format YYYY-MM-DD
        function getCurrentDate() {
            var today = new Date();
            var month = String(today.getMonth() + 1).padStart(2, '0');
            var day = String(today.getDate()).padStart(2, '0');
            var year = today.getFullYear();
            return year + '-' + month + '-' + day;
        }

        // Set the value of the date input field to the current date
        document.getElementById('date').value = getCurrentDate();
 
</script>
<script src="https://cdn.tiny.cloud/1/1xpae6vwk3sbnv03ga4b1lznhp5ucril1rznh7lh9cw8ilsi/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea',
            plugins: 'lists link',
            toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | link',
            menubar: true
        });
    </script>
<script>
    document.getElementById('show-popup').addEventListener('click', function() {
        document.getElementById('popup-overlay').classList.add('show');
        document.getElementById('popup').classList.add('show');
    });

    document.getElementById('popup-overlay').addEventListener('click', function() {
        document.getElementById('popup-overlay').classList.remove('show');
        document.getElementById('popup').classList.remove('show');
    });

    document.getElementById('submit-btn').addEventListener('click', function() {
        let selectedPositions = [];
        document.querySelectorAll('input[name="positions[]"]:checked').forEach((checkbox) => {
            selectedPositions.push(checkbox.value);
        });

        if (selectedPositions.length === 0) {
             // Show SweetAlert for action already taken
                    Swal.fire({
                        icon: 'error',
                        title: 'Action Required',
                        text: 'Choose atleast one Position'
                    }).then(function () {
                        window.location.href = 'create_memo.php';
                    });
            return;
        }

        // Handle the selected positions
        console.log("Selected positions:", selectedPositions);

        // Optionally, you can send the selected positions to the server using AJAX
        // Example:
        // let xhr = new XMLHttpRequest();
        // xhr.open("POST", "your-server-endpoint.php", true);
        // xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        // xhr.onreadystatechange = function () {
        //     if (xhr.readyState === 4 && xhr.status === 200) {
        //         alert("Form submitted successfully!");
        //     }
        // };
        // xhr.send("positions=" + JSON.stringify(selectedPositions));

        document.getElementById('popup-overlay').classList.remove('show');
        document.getElementById('popup').classList.remove('show');
    });
</script>

</body>
</html>
