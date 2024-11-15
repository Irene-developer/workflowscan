<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="x-icon" href="KCBLLOGO.PNG">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Employees</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!--link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'-->
    <link rel="stylesheet" href="register_style.css"></link>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet" href="assets/fas fas fas 3/css/font-awesome.min.css">
    <link href="assets/css/responsive.css" rel="stylesheet" type="text/css"/>     
    <link rel="stylesheet" href="assets/sweetalert2/sweetalert2.min.css">
    <script src="assets/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="assets/js/jquery/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>

<body>

<?php
// Establish a database connection
include 'include.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $last_name = $_POST['last_name'];
    $middle_name = $_POST['middle_name'];
    $first_name = $_POST['first_name'];
    $department_name = $_POST['departmentName'];
    $email = $_POST['email'];
    $password = $_POST['Password'];
    $Position_name = $_POST['positionName'];
    $employee_type = $_POST['Show_Employee_Types'];

    // Check if all fields are empty
    if (empty($last_name) && empty($middle_name) && empty($first_name) && empty($department_name) && empty($email) && empty($password) && empty($Position_name) && empty($employee_type)) {
        echo '<script>alert("Please fill in at least one field.")</script>';
    } else {
        // Validate password
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()\-_=+{};:,<.>])[A-Za-z\d!@#$%^&*()\-_=+{};:,<>.]{8,}$/', $password)) {
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Password must contain at least one uppercase letter, one lowercase letter, one symbol, one number, and have a total length of eight characters or more.',
            showConfirmButton: false,
            timer: 1500
        }).then(function () {
            window.location.href = 'register_employees.php';
        });
    </script>";

} else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // SQL query to insert data into the database
            $sql = "INSERT INTO employee_access (last_name, middle_name, first_name, department_name, email, password, Position_name, employee_type)
                    VALUES ('$last_name', '$middle_name', '$first_name', '$department_name', '$email', '$hashed_password', '$Position_name', '$employee_type')";

           if ($conn->query($sql) === TRUE) {
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'New Employee Created successfully',
            showConfirmButton: false,
            timer: 1500
        }).then(function () {
            window.location.href = 'dashboard.php';
        });
    </script>";
} else {
    if ($conn->errno == 1062) { // MySQL error code for duplicate entry
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Duplicate entry for email or other unique field.',
                showConfirmButton: false,
                timer: 3000
            }).then(function () {
                window.location.href = 'dashboard.php';
            });
        </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

        }
    }
}

// Close connection
$conn->close();
?>




<div class="container">



    <div class="section-employees">
        

        <h2>Employee Information</h2>
        <form action="" method="post">

            

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name">

            <label for="middle_name">Middle Name:</label>
            <input type="text" id="middle_name" name="middle_name">

            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name">

            <label for="departmentName"><b>Department Name</b></label>
            <input type="text" placeholder="Enter Department Name" name="departmentName" required id="departmentNameInput">
            <div id="departmentNamesDropdown"></div>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email"><br>

             <label for="Password">Password:</label>
             <input type="password" id="Password" name="Password" minlength="8" required>
             <div id="passwordFeedback"></div><br>

             <label for="Position">Position:</label>
             <input type="text" placeholder="Enter Position Name"  name="positionName" id="PositionNameInput"><br>
             <div id="positionNamesDropdown" class="scrollable-dropdown">
    <!-- Your content here -->
             </div>
            
            <label for="Show_Employee_Types">Show Employee Types</label>
            <input type="text" id="employeetypedropdown" name="Show_Employee_Types" onclick="toggleDropdown()">
            <div id="dropdownContent" style="display: none;">
              <select id="employeeTypeDropdown" onchange="updateInputField()">
                <option value="1">Department_Head</option>
                <option value="2">Employee</option>
                <option value="3">Admin</option>
                <option value="4">Finance/Administration</option>
              </select>
            </div>



            <input type="submit" value="Submit" class="submit-button">
        </form>
    </div>
    <div class="section-resett">
        <h2>Resetting User</h2>
        <!-- Add form fields for resetting user information -->
        <form action="">
<input type="text" id="username" name="user_name" style="max-width: 80px;">
<div id="usernameDropdown"></div>

<label for="UserName">Username:</label>
<input type="text" id="user_name" name="user_name">

        </form>
<!-- Button -->
<div class="edit_div" style="display: flex; justify-content: center; align-items: center;">
    <input type="button" value="Edit" class="edituser-button" id="edituserBtn" style="background-color: #3385ff; /* Blue */
        border: none;
        color: white;
        padding: 5px;
        text-align: center;
        text-decoration: none;
        display: block;
        max-width: 50px;
        margin: 4px 2px;
        cursor: pointer;">
</div>

    </div>
</div>
<!-- Popup Modal -->
<div id="popupModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 80%; max-width: 800px; height: 80%; max-height: 600px; background-color: white; border: 1px solid #ccc; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.2); z-index: 1000; overflow-y: auto;">
    <h2>Edit Details</h2>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="border: 1px solid #ddd; padding: 10px; color: white; background-color: #3385ff;">ID</th>
                <th style="border: 1px solid #ddd; padding: 10px; color: white; background-color: #3385ff;">Username</th>
                <th style="border: 1px solid #ddd; padding: 10px; color: white; background-color: #3385ff;">Email</th>
                <th style="border: 1px solid #ddd; padding: 10px; color: white; background-color: #3385ff; text-align: center;">Actions</th>
            </tr>
        </thead>
        <tbody id="employeeTableBody">
            <!-- Data rows will be inserted here by JavaScript -->
        </tbody>
    </table>
    <button id="closePopupBtn" style="background-color: #3385ff; border: none; color: white; padding: 10px; text-align: center; text-decoration: none; display: inline-block; cursor: pointer; font-size: 14px;">Close</button>
</div>


<div id="overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999;"></div>


<!-- Overlay -->
<div id="overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999;"></div>

   <footer>
        <!-- Footer content goes here -->
        <p>&copy;Kilimanjaro Co-operative Bank 2024<span style="color: #44ad49">(KCBL).</span> All rights reserved.</p>
    </footer>
<script>
    document.getElementById('edituserBtn').addEventListener('click', function() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetch_employee_access_details.php', true);
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                var response = JSON.parse(xhr.responseText);
                var popupContent = '<h2 style="text-align: center;">Edit Details</h2>' +
                    '<table style="width: 100%; border-collapse: collapse;">' +
                    '<thead>' +
                    '<tr>' +
                    '<th style="border: 1px solid #ddd; padding: 10px; color: white; background-color: #3385ff;">ID</th>' +
                    '<th style="border: 1px solid #ddd; padding: 10px; color: white; background-color: #3385ff;">Username</th>' +
                    '<th style="border: 1px solid #ddd; padding: 10px; color: white; background-color: #3385ff;">Email</th>' +
                    '<th style="border: 1px solid #ddd; padding: 10px; color: white; background-color: #3385ff; text-align: center;">Actions</th>' +
                    '</tr>' +
                    '</thead>' +
                    '<tbody>';

                if (response.length > 0) {
                    response.forEach(function(employee) {
                        popupContent += '<tr>' +
                            '<td style="border: 1px solid #ddd; padding: 10px;">' + employee.id + '</td>' +
                            '<td style="border: 1px solid #ddd; padding: 10px;">' + employee.username + '</td>' +
                            '<td style="border: 1px solid #ddd; padding: 10px;">' + employee.email + '</td>' +
                            '<td style="border: 1px solid #ddd; padding: 10px; text-align: center;">' +
                            '<span class="edit-icon" style="cursor: pointer; margin: 0 5px; font-size: 16px; color: #4caf50;" title="Edit">&#9998;</span>' +
                            '<span class="delete-icon" style="cursor: pointer; margin: 0 5px; font-size: 16px; color: #f44336;" title="Delete">&#10060;</span>' +
                            '</td>' +
                            '</tr>';
                    });
                } else {
                    popupContent += '<tr><td colspan="4" style="border: 1px solid #ddd; padding: 10px; text-align: center;">No records found.</td></tr>';
                }

                popupContent += '</tbody></table>' +
                    '<button id="closePopupBtn" style="background-color: #3385ff; border: none; color: white; padding: 10px; text-align: center; text-decoration: none; display: inline-block; cursor: pointer; font-size: 14px;">Close</button>';

                document.getElementById('popupModal').innerHTML = popupContent;
                document.getElementById('popupModal').style.display = 'block';
                document.getElementById('overlay').style.display = 'block';

                // Add event listener to close button
                document.getElementById('closePopupBtn').addEventListener('click', function() {
                    document.getElementById('popupModal').style.display = 'none';
                    document.getElementById('overlay').style.display = 'none';
                });

               // Add click event listeners for edit and delete icons
                document.querySelectorAll('.edit-icon').forEach(function(icon) {
                    icon.addEventListener('click', function() {
                        var employeeId = this.closest('tr').children[0].textContent;
                        showEditPopup(employeeId);
                    });
                });

               document.querySelectorAll('.delete-icon').forEach(function(icon) {
    icon.addEventListener('click', function() {
        // Get the ID from the closest table row
        var employeeId = this.closest('tr').children[0].textContent;
        
        // Show SweetAlert confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: 'You will not be able to recover this record!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with deletion if confirmed
                deleteEmployee(employeeId);
            }
        });
    });
});

function deleteEmployee(employeeId) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'delete_employee_access.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            Swal.fire(
                'Deleted!',
                'The record has been deleted.',
                'success'
            ).then(() => {
                // Optionally, remove the row from the table
                var row = document.querySelector('tr td:first-child').textContent.trim() === employeeId;
                if (row) row.closest('tr').remove();
                // Or reload the page to reflect the deletion
                // location.reload(); 
            });
        } else {
            Swal.fire(
                'Error!',
                'An error occurred while deleting the record.',
                'error'
            );
        }
    };
    xhr.send('employeeId=' + encodeURIComponent(employeeId));
}

            }
        };
        xhr.send();
    });


function showEditPopup(employeeId) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_employee_details_for_edit.php?id=' + employeeId, true);
    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            var employee = JSON.parse(xhr.responseText);
            var popupContent = '<h2 style="text-align: center;">Edit Details for ID ' + employee.id + '</h2>' +
                '<form id="editForm">' +
                '<label for="last_name">Last Name:</label>' +
                '<input type="text" id="last_name" name="last_name" value="' + employee.last_name + '" style="display: block; width: 100%; margin-bottom: 10px; padding: 8px; border: 1px solid #ddd;"><br>' +
                '<label for="middle_name">Middle Name:</label>' +
                '<input type="text" id="middle_name" name="middle_name" value="' + employee.middle_name + '" style="display: block; width: 100%; margin-bottom: 10px; padding: 8px; border: 1px solid #ddd;"><br>' +
                '<label for="first_name">First Name:</label>' +
                '<input type="text" id="first_name" name="first_name" value="' + employee.first_name + '" style="display: block; width: 100%; margin-bottom: 10px; padding: 8px; border: 1px solid #ddd;"><br>' +
                '<label for="department_name">Department:</label>' +
                '<input type="text" id="department_name" name="department_name" value="' + employee.department_name + '" style="display: block; width: 100%; margin-bottom: 10px; padding: 8px; border: 1px solid #ddd;"><br>' +
                '<label for="email">Email:</label>' +
                '<input type="email" id="email" name="email" value="' + employee.email + '" style="display: block; width: 100%; margin-bottom: 10px; padding: 8px; border: 1px solid #ddd;"><br>' +
                '<label for="password">Password:</label>' +
                '<input type="password" id="password" name="password" value="' + employee.password + '" style="display: block; width: 100%; margin-bottom: 10px; padding: 8px; border: 1px solid #ddd;"><br>' +
                '<label for="Position_name">Position:</label>' +
                '<input type="text" id="Position_name" name="Position_name" value="' + employee.Position_name + '" style="display: block; width: 100%; margin-bottom: 10px; padding: 8px; border: 1px solid #ddd;"><br>' +
                '<label for="employee_type">Employee Type:</label>' +
                '<input type="text" id="employee_type" name="employee_type" value="' + employee.employee_type + '" style="display: block; width: 100%; margin-bottom: 10px; padding: 8px; border: 1px solid #ddd;"><br>' +
                '<input type="hidden" id="employeeId" name="employeeId" value="' + employee.id + '">' +
                '<button type="submit" style="background-color: #3385ff; border: none; color: white; padding: 10px; text-align: center; text-decoration: none; display: inline-block; cursor: pointer; font-size: 14px;">Save Changes</button>' +
                '</form>' +
                '<button id="closeEditPopupBtn" style="background-color: #f44336; border: none; color: white; padding: 10px; text-align: center; text-decoration: none; display: inline-block; cursor: pointer; font-size: 14px;">Close</button>';

            document.getElementById('popupModal').innerHTML = popupContent;
            document.getElementById('popupModal').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';

            // Add event listener to close edit popup
            document.getElementById('closeEditPopupBtn').addEventListener('click', function() {
                document.getElementById('popupModal').style.display = 'none';
                document.getElementById('overlay').style.display = 'none';
            });

            // Add event listener to form submit
            document.getElementById('editForm').addEventListener('submit', function(event) {
                event.preventDefault();

                var formData = new FormData(this);

                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'update_employee_access_details.php', true);

                xhr.onload = function() {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Details updated successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            document.getElementById('popupModal').style.display = 'none';
                            document.getElementById('overlay').style.display = 'none';
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Error updating details.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                };

                xhr.send(formData);
            });

        } else {
            Swal.fire({
                title: 'Error!',
                text: 'Error fetching employee details.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    };
    xhr.send();
}



    // Optional: Close popup when clicking outside the modal
    document.getElementById('overlay').addEventListener('click', function() {
        document.getElementById('popupModal').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
    });
</script>

<script>
	
	// Get all dropdown buttons
var dropdownBtns = document.querySelectorAll('.dropdown-btn');

// Attach click event listener to each dropdown button
dropdownBtns.forEach(function(btn) {
    btn.addEventListener('click', function() {
        // Toggle the display of dropdown content
        var dropdownContent = this.nextElementSibling;
        if (dropdownContent.style.display === 'block') {
            dropdownContent.style.display = 'none';
        } else {
            dropdownContent.style.display = 'block';
        }
    });
});


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
//
function toggleDropdown() {
  var dropdownContent = document.getElementById("dropdownContent");
  if (dropdownContent.style.display === "none") {
    dropdownContent.style.display = "block";
  } else {
    dropdownContent.style.display = "none";
  }
}

function updateInputField() {
  var selectedOption = document.getElementById("employeeTypeDropdown").value;
  document.getElementById("employeetypedropdown").value = getOptionText(selectedOption);
}

function getOptionText(optionValue) {
  switch(optionValue) {
    case '1':
      return "Department_Head";
    case '2':
      return "Employee";
    case '3':
      return "Admin";
    case '4':
      return "Finance/Administration";
    default:
      return "";
  }
}



//for position 
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

//username

$(document).ready(function(){
    // Function to fetch user details based on selected username
    function fetchUserDetails(username) {
        $.ajax({
            url: 'fetch_user_details.php', // Replace with the actual PHP file for fetching user details
            type: 'POST',
            data: { username: username },
            success: function(response) {
                // Parse JSON response
                var userDetails = JSON.parse(response);

                // Populate input fields with retrieved details
                $('#last_name').val(userDetails.last_name);
                $('#middle_name').val(userDetails.middle_name);
                $('#first_name').val(userDetails.first_name);
                $('#departmentNameInput').val(userDetails.department_name);
                $('#email').val(userDetails.email);
                $('#PositionNameInput').val(userDetails.position_name);
                $('#user_name').val(userDetails.username);
            }
        });
    }

    $('#username').click(function(){
        // Fetch usernames using AJAX
        $.ajax({
            url: 'fetch_usernames.php', // Replace with the actual PHP file for fetching usernames
            type: 'GET',
            success: function(response){
                // Parse JSON response
                var usernames = JSON.parse(response);
                
                // Display usernames in a dropdown
                $('#usernameDropdown').empty();
                usernames.forEach(function(username){
                    var dropdownItem = $('<div class="dropdown-item">' + username + '</div>');
                    dropdownItem.click(function(){
                        $('#username').val(username);
                        $('#usernameDropdown').empty();

                        // Fetch user details based on selected username
                        fetchUserDetails(username);
                    });
                    $('#usernameDropdown').append(dropdownItem);
                });
                $('#usernameDropdown').slideDown();
            }
        });
    });
    
    $(document).click(function(e) {
        if (!$(e.target).is('#username') && !$(e.target).closest('#usernameDropdown').length) {
            $('#usernameDropdown').slideUp();
        }
    });
});
//password 

 const passwordInput = document.getElementById("Password");
    const passwordFeedback = document.getElementById("passwordFeedback");

    passwordInput.addEventListener("input", function() {
        const password = passwordInput.value;
        const strength = calculatePasswordStrength(password);
        let feedback = "";

        if (password.length === 0) {
            feedback = "Password is required.";
        } else if (strength < 2) {
            feedback = "Weak password. Please include at least one uppercase letter, one lowercase letter, one digit, and one special character.";
        } else if (strength < 7) {
            feedback = "Moderate password. Consider adding more characters and including uppercase letters, lowercase letters, digits, and special characters.";
        } else {
            feedback = "Strong password.";
        }

        passwordFeedback.textContent = feedback;
    });

    function calculatePasswordStrength(password) {
        let strength = 0;
        const regex = {
            uppercase: /[A-Z]/,
            lowercase: /[a-z]/,
            digit: /\d/,
            special: /[^A-Za-z0-9]/
        };

        if (regex.uppercase.test(password)) strength++;
        if (regex.lowercase.test(password)) strength++;
        if (regex.digit.test(password)) strength++;
        if (regex.special.test(password)) strength++;

        return strength;
    }
    //RESETT PASSWORD 

$(document).ready(function(){
    $('#resetPasswordBtn').click(function(){
        var username = $('#user_name').val();
        var password = $('#Passwordr').val();
        var confirmPassword = $('#Confirm_Password').val();

        // Validation: Check if passwords match
        if (password !== confirmPassword) {
            alert("Passwords do not match");
            return;
        }

        // AJAX request to reset password
        $.ajax({
            url: 'reset_password.php',
            type: 'POST',
            data: { username: username, password: password },
            success: function(response){
        Swal.fire({
            icon: 'success',
            title: 'Password Resett successfully',
            showConfirmButton: false,
            timer: 1500
        }).then(function () {
            window.location.href = 'register_employees.php';
        });
            },
            error: function(xhr, status, error){
                console.error(xhr.responseText);
            }
        });
    });
});
 

function toggleDropdown() {
  var dropdownContent = document.getElementById("dropdownContent");
  if (dropdownContent.style.display === "none") {
    dropdownContent.style.display = "block";
  } else {
    dropdownContent.style.display = "none";
  }
}

function updateInputField() {
  var selectedOption = document.getElementById("employeeTypeDropdown").value;
  var employeeTypeText = getOptionText(selectedOption);

  // Check if the selected employee type matches those found in the database
  var employeeTypeInDatabase = ["Department_Head", "Employee", "Admin", "Finance/Administration"];
  if (employeeTypeInDatabase.includes(employeeTypeText)) {
    document.getElementById("employeetypedropdown").value = selectedOption;
    // Allow form submission
    document.getElementById("yourFormId").submit();
  } else {
    // Display error message or handle invalid selection
    alert("Invalid employee type selection!");
    // Optionally, prevent form submission
    return false;
  }
}

function getOptionText(optionValue) {
  switch(optionValue) {
    case '1':
      return "Department_Head";
    case '2':
      return "Employee";
    case '3':
      return "Admin";
    case '4':
      return "Finance/Administration";
    default:
      return "";
  }
}


</script>
<?php include 'footer.php'; ?> 
</body>
</html>
