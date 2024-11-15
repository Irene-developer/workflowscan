<?php 
//session_start();
// Assuming $username is set in the session
        include 'include.php'; // Include database connection
        include('session_timeout.php');
        
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="x-icon" href="KCBLLOGO.PNG">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Memo</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" type="text/css" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        body {
            font-family: "Open Sans", Arial, sans-serif;
            display: flex; /* Add flex display */
            flex-direction: row; /* Stack items horizontally */
            align-items: flex-start; /* Align items to the start (top) */
            border: 1px solid #ccc;
            background-color: #fff;
            padding: 10px;
            width: 95%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin: 20px auto;
        }

        /* Style for the memo details */
        .memo-details {
            border: 1px solid #ccc;
            background-color: #fff;
            padding: 5px;
            margin-bottom: 20px; /* Add space between memo details */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin: 30px;
            width: 90%;
        }

        /* Style for individual detail items */
        .memo-details p {
            margin: 3px 0;
            /* Ensure full width for each item */
            text-align:left; /* Center align text */
            background-color: #3385ff ;
            padding: 10px;
            border-radius: 0.3em;
            color: white;
        }

     

        /* Style for the memo content */
        .memo-content {
            flex: 1; /* Allow content to grow and take remaining space */
            text-align: left;
            margin-top: 20px;
            border-top: 1px solid #ccc; /* Add a top border for separation */
            padding-top: 20px;
             /* Add padding to the top */
        }

        /* Style for individual content items */
        .memo-content p {
            margin: 10px 0 0 20px;
            font-size: 16px; /* Adjust font size */
            line-height: 1.6; /* Adjust line height for readability */
            color: #333; /* Set text color */
        }

       /* Style for the actions container */
.actions-container {
    display: flex;
    flex-direction: column; /* Arrange children in a vertical column */
    justify-content: space-between;
    margin-top: 20px;
}

        /* Style for action buttons */
        .approve-button, .reject-button, .add_approval-button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Style for the approve button */
        .approve-button, .add_approval-button {
            background-color: transparent;
            color: #28a745;
        }

        .add_approval-button, .approve-button:hover {
            background-color: white;
        }

        /* Style for the reject button */
        .reject-button {
            background-color: transparent;
            color: #dc3545;
        }

        .reject-button:hover {
            background-color: white;
        }

        .memo-content-c1 {
            display: flex;
            flex-direction: column; /* Display memo details in a column */
        }

        /* Styles for the overlay */
        .overlay {
            position: fixed; /* Fixed position */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
            z-index: 999; /* Ensure it appears on top of other elements */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Styles for the popup */
        .popup {
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3); /* Shadow effect */
        }

        /* Styles for the comment textarea */
        .comment-input {
            width: 100%;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        /* Styles for the send button */
        .send-comment-button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #4CAF50; /* Green background */
            color: white; /* White text color */
            transition-duration: 0.4s;
        }

        .send-comment-button:hover {
            background-color: #45a049; /* Darker green on hover */
        }
        /* Modal CSS */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 9999; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}


.modalr, .modaladd {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 9999; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  background-color: #fefefe;
  margin: 15% auto; /* 15% from the top and centered */
  padding: 20px;
  border: 1px solid #888;
  width: 50%; /* Could be more or less, depending on screen size */
}

/* Close Button */
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
}

/* Send button styles */
.send-comment-button {
  margin-top: 10px; /* Add space between input and button */
  padding: 10px 20px;
  font-size: 16px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  background-color: #4CAF50; /* Green background */
  color: white; /* White text color */
}
/* Send button styles */
.send-reverse-comment-button {
  margin-top: 10px; /* Add space between input and button */
  padding: 10px 20px;
  font-size: 16px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  background-color: #4CAF50; /* Green background */
  color: white; /* White text color */
}
.send-comment-button:hover {
  background-color: #45a049; /* Darker green on hover */
}
.no-action {
    color: red;
    font-weight: bold;
    font-size: 16px;
    text-align: center;
    margin: 10px 0;
    padding: 10px;
    border: 1px solid red;
    background-color: #ffe6e6;
    border-radius: 5px;

}
/* Modal container */
.modaladd {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1000; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgba(0, 0, 0, 0.5); /* Black w/ opacity */
}

/* Modal content */
.modal-content {
    background-color: #fefefe;
    margin: 15% auto; /* 15% from the top and centered */
    padding: 20px;
    border: 1px solid #888;
    width: 80%; /* Could be more or less, depending on screen size */
    max-width: 600px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* Close button */
.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

/* Form header */
.form-header {
    text-align: center;
    font-size: 20px;
    margin-bottom: 20px;
    color: #333;
}

/* Dropdown container */
#dropdown-container {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

#dropdown-container label {
    margin-right: 10px;
    font-weight: bold;
    color: #333;
}

#dropdown-container select {
    padding: 8px 12px;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: #f9f9f9;
    width: 100%;
    max-width: 300px;
}

/* Dropdown toggle icon */
.dropdown-toggle {
    margin-left: 10px;
    color: #007bff;
    font-size: 18px;
    text-decoration: none;
}

.dropdown-toggle i {
    cursor: pointer;
}

.dropdown-toggle:hover i {
    color: #0056b3;
}

/* Comment input */
.comment-input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-bottom: 15px;
    font-size: 16px;
    resize: none;
}

/* Send comment button */
.send-comment-button {
    background-color: #28a745;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
}

.send-comment-button:hover {
    background-color: #218838;
}

/* Container for positioning */
.popup-container {
    position: relative;
    display: inline-block; /* Ensure it wraps tightly around the content */
}

/* Styles for the popup */
.popup-content {
    display: none;
    position: absolute;
    top: 50%; /* Center vertically relative to the button */
    right: 110%; /* Position the popup to the left of the button */
    transform: translateY(-50%); /* Center vertically relative to its height */
    background-color: #333; /* Dark background color */
    color: #fff; /* Light text color */
    border-radius: 8px; /* Rounded corners */
    padding: 15px; /* Padding around the content */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3); /* Enhanced shadow effect */
    white-space: nowrap; /* Prevents text from wrapping */
    z-index: 1000; /* Ensures the popup is above other content */
    font-size: 14px; /* Adjust font size for readability */
    width: 200px; /* Set a fixed width for consistent styling */
    text-align: center; /* Center text */
    border: 1px solid #444; /* Subtle border */
    opacity: 0; /* Start with hidden opacity for fade-in effect */
    transition: opacity 0.3s ease; /* Smooth fade-in transition */
}

/* Show the popup when hovering over the trigger element */
.popup-container:hover .popup-content {
    display: block;
    opacity: 1; /* Show the popup with full opacity */
}
            .tooltip {
        visibility: hidden;
        background-color: #333;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px;
        position: absolute;
        z-index: 1;
        bottom: 100%;
        left: 50%;
        margin-left: -60px;
        width: 200px;
        opacity: 0;
        transition: opacity 0.3s;
    }

    td:hover .tooltip {
        visibility: visible;
        opacity: 1;
    }

    .short-text {
        cursor: pointer;
    }
<style>
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    overflow: auto;
}

.modal-content {
    background: white;
    padding: 20px;
    border-radius: 8px;
    width: 90%;
    max-width: 1100px;
    position: relative;
}

.closedoc {
    position: absolute;
    top: 10px;
    right: 20px;
    font-size: 24px;
    cursor: pointer;
}
</style>
    </style>
</head>

<body>
<?php
// Start session if not already started
//session_start();

// Include database connection or any other necessary files
// include 'include.php';

// Check if the 'id' parameter is set in the URL
if(isset($_GET['id'])) {
    // Sanitize the id parameter to prevent SQL injection
    $memo_id = intval($_GET['id']); // Assuming id is an integer
    $_SESSION['memo_id'] = $memo_id;
    // Include database connection
    include 'include.php'; // Adjust this to your database connection file path

    // Query to fetch memo details based on the provided id
    $sql = "SELECT * FROM memos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $memo_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if memo with the provided id exists
    if($result->num_rows > 0) {
        // Fetch memo details
        $memo = $result->fetch_assoc();
        
        // Display memo details
        echo "<div class='memo-content-c1'>";
echo "<table class='memo-details' border='1'>";
echo "<tr><th style='background-color: #3385ff; color: white'>Field</th><th style='background-color: #3385ff; color: white'>Details</th></tr>";
echo "<tr><td>Memo ID</td><td>" . htmlspecialchars($memo['id']) . "</td></tr>";
echo "<tr><td>Name</td><td>" . htmlspecialchars($memo['username']) . "</td></tr>";
echo "<tr><td>Date</td><td>" . htmlspecialchars($memo['date']) . "</td></tr>";
echo "<tr><td>Department Name</td><td>" . htmlspecialchars($memo['departmentName']) . "</td></tr>";
echo "<tr><td>Reference No</td><td>" . htmlspecialchars($memo['refNo']) . "</td></tr>";
echo "<tr><td>Classification</td><td>" . htmlspecialchars($memo['classfication']) . "</td></tr>";
// Add more details as needed
echo "</table>";

        // Display memo details
        // Add table to display position name, status, and signature
       echo "<table class='memo-details' border='1'>";
        // Fetch data from the memo_action table
$query = "SELECT username, status, comment, signature_path FROM memo_action WHERE memo_id = ?";
$stmt = $conn->prepare($query);
$stmt -> bind_param("i", $memo_id);
$stmt->execute();
$result = $stmt->get_result();
//$result = mysqli_query($conn, $query);

echo "<tr><th style='background-color: #3385ff; color: white'>Name</th><th style='background-color: #3385ff; color: white'>Status</th><th style='background-color: #3385ff; color: white'>Comment</th><th style='background-color: #3385ff; color: white'>Signature</th></tr>";
//$result = mysqli_query($conn, $query);
 // Loop through each row of the result
    while($row = mysqli_fetch_assoc($result)) {
       echo "<tr>";
echo "<td style='padding: 3px; border-bottom: 1px solid #dddddd;'>".$row['username']."</td>";
echo "<td style='padding: 3px; border-bottom: 1px solid #dddddd;'>".$row['status']."</td>";
            $comment = $row['comment'];
        $shortComment = substr($comment, 0, 5);

        echo "
        <td style='padding: 8px; border-bottom: 1px solid #dddddd; position: relative;'>
        <span class='short-text'>$shortComment</span>
        <div class='tooltip'>$comment</div>
        </td>
";
//echo "<td style='padding: 3px; border-bottom: 1px solid #dddddd;'>".$row['comment']."</td>";
echo "<td style='padding: 3px; border-bottom: 1px solid #dddddd; text-align: center;'><img src='".$row['signature_path']."' alt='Signature' style='max-width: 100px; max-height: 50px;'></td>";
echo "</tr>";

    }

echo "<tr>";
$username = "";
        // echo "<p>" . $_SESSION['Position_name'] . "</p>";
// Use Position_name from $_SESSION for comparison
            
//echo "<td>" . $_SESSION['Position_name'] . "</td>";
            

//echo "<td>" . $memo['status'] . "</td>";
// Start the session
//session_start();

// Check if the username is set in the session
if(isset($_SESSION['username'])) {
    // Retrieve the username from the session
    $username = $_SESSION['username'];

    // Prepare SQL query to fetch comments for the current username
    
    $sql_comment = "SELECT comment 
FROM memo_action 
WHERE username = ? 
AND date = (
    SELECT MAX(date) 
    FROM memo_action 
    WHERE username = ?
);
";
    $stmt = $conn->prepare($sql_comment);
    //$stmt->bind_param("ss", $username, $username);
    //$stmt->execute();
    //$result = $stmt->get_result();

    // Fetch comments and display them
    while ($row = $result->fetch_assoc()) {
        $comment = $row['comment'];
        echo "<td>" . $comment . "</td>";
    }
} else {
    // Handle case where username is not set in the session
    echo "Username not found in session.";
}

// Assuming $conn is your database connection object
// Assuming $username is set correctly
// Check if the status is 'approved'


//below is the script hiden for stopping fetch signature and show details before any approval and decline action taken

if ($memo['status'] === 'approve' || $memo['status'] === 'decline') {
    // Start the session
    //session_start();

    // Retrieve the username from the session
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];

        // Include database connection
        include 'include.php';

        // Retrieve and display signature from memo_action table
        $sql_signature = "SELECT 
    ma.memo_id, 
    ma.username, 
    ma.status, 
    ma.comment, 
    COALESCE(s.signature_path, ma.signature_path) AS signature_path, 
    ma.Position_name 
FROM 
    memo_action ma 
LEFT JOIN 
    signature s ON ma.username = s.username 
WHERE 
    ma.username = ? 
    AND ma.status = 'approved' OR ma.status = 'declined'";

        $stmt_signature = $conn->prepare($sql_signature);

        // Check if the statement was prepared successfully
        if ($stmt_signature) {
            // Bind parameter and execute the statement
            $stmt_signature->bind_param("i", $username); // Assuming $memoId contains the memo_id
            $stmt_signature->execute();

            // Get the result
            $result_signature = $stmt_signature->get_result();

            // Check if there are rows in the result
            if ($result_signature->num_rows > 0) {
                $row_signature = $result_signature->fetch_assoc();
                $signature_path = $row_signature['signature_path'];
                echo "<td style='text-align: center;'><img src='" . $signature_path . "' alt='Signature' style='width: 40px; height: 20px;'></td>";
            } else {
                echo "<td>No signature available</td>";
            }

            // Close the statement
            $stmt_signature->close();
        } else {
            // Handle the case where the statement preparation failed
            echo "Failed to prepare statement: " . $conn->error;
        }
    } else {
        echo "<td>No username available in session</td>";
    }
} else {
    // If status is not 'approved', don't fetch the signature
    //echo "<td></td>"; // Empty cell
}



echo "</tr>";


echo "</table>";

        // Close memo-details div
        echo "</div>";
        echo "</div>";
        echo "<div class='memo-content'>";
        echo "<div style='display: flex; align-items: center; align-contents: center;'>";
        echo "<p style='font-weight: bold;'> Subject:</p>";
        echo "<p style='font-weight: bold;'>" . $memo['subject'] . "</p>";
        echo "</div>";

        echo "<p>" . $memo['content'] . "</p>";
        // Print button CSS
// CSS for print button and hiding elements when printing
echo "<style>";
echo ".print-button {";
echo "    display: block;";
//echo "    max-width: 120px;";
echo "    text-decoration: none;";
//echo "    padding: 10px 20px;";
echo "    font-size: 16px;";
//echo "    background-color: #4CAF50;";
echo "    color: white;";
echo "    border: none;";
echo "    border-radius: 5px;";
echo "    cursor: pointer;";
echo "    transition: background-color 0.3s;";
echo "}";
echo ".print-button:hover {";
//echo "    background-color: #45a049;";
echo "}";
echo "@media print {";
echo "    .print-button,";
echo "    .actions-container {";
echo "        display: none !important;";
echo "    }";
echo "}";
echo ".preview-button {";
//echo "    max-width: 120px;";
echo "    text-decoration: none;";
echo "    display: block;";
//echo "    padding: 10px 20px;";
echo "    font-size: 16px;";
echo "    background-color: #4CAF50;";
echo "    color: white;";
echo "    border: none;";
echo "    border-radius: 5px;";
echo "    cursor: pointer;";
echo "    transition: background-color 0.3s;";
echo "}";
echo ".preview-button:hover {";
echo "    background-color: #45a049;";
echo "}";
echo "@media preview {";
echo "    .print-button,";
echo "    .actions-container {";
echo "        display: none !important;";
echo "    }";
echo "}";
echo "</style>";


  
        echo "</div>";
        echo "<div class='actions-container'>";

// Assuming $memo_id is available from your context
$memo_id = $memo['id']; // Replace with the actual memo ID variable
$username = $_SESSION['username']; // Assuming Position_name is stored in the session

// Query to check if the Position_name already exists for the specific memo_id
$query = "SELECT * FROM memo_action WHERE memo_id = ? AND username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $memo_id, $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // If Position_name exists, show "no action allowed"
    echo "<div class='no-action'>No action allowed</div>";
} else {
    // If Position_name does not exist, show approve and reject buttons
    echo "<div style='text-align: center; margin-top: 20px;padding: 10px;'>";
    echo "<button class='approve-button' data-memo-id='" . $memo_id . "' style='color: blue; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background-color: lightgreen;'><i class='fa fa-check' style='color: white;' title='Approve'></i></button>";
   echo "</div>";

     echo "<div style='text-align: center; margin-top: 20px;padding: 10px;'>";
    echo "<button class='reject-button' data-memo-idr='" . $memo_id . "'style='color: blue; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background-color: lightcoral;'><i class='fa fa-remove' style='color: white;' title='Reject'></i></button>";
    echo "</div>";
    echo "<div style='text-align: center; margin-top: 20px;padding: 10px;'>";
    echo "<button class='add_approval-button' data-memo-idad='" . $memo_id . "' style='color: blue; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background-color: #3385ff;' title='Add Approval'>
        <i class='fa fa-plus-circle' style='color: white;'></i>
      </button>";
echo "</div>";


 
}



   echo "<div style='text-align: center; margin-top: 20px;padding: 10px;'>";
echo "<a href='generate_pdf.php?id=$memo_id' class='print-button' style='display: inline-flex; width: 40px; height: 40px; border-radius: 50%; background-color: #f0f0f0; align-items: center; justify-content: center;' title='Print'>
        <i class='fa fa-print' style='color: green;'></i>
      </a>";
echo "</div>";

echo "<div style='text-align: center; margin-top: 20px;padding: 10px;'>";
echo "<a href='dashboard.php' style='display: inline-flex; text-decoration: none; width: 40px; height: 40px; border-radius: 50%; background-color: #f0f0f0; align-items: center; justify-content: center; color: #3385ff;' title='Back'>
        <i class='fa fa-arrow-left'></i>
      </a>";
echo "</div>";

echo "<div class='popup-container' style='text-align: center; margin-top: 20px; padding: 10px; position: relative;'>";
echo "<a href='#' 
       style='display: inline-flex; text-decoration: none; width: 40px; height: 40px; border-radius: 50%; background-color: #f0f0f0; align-items: center; justify-content: center; color: #3385ff;' 
       title='Added message'
       class='popup-trigger' 
       data-memo-id='" . intval($_GET['id']) . "'>
        <i class='fa fa-commenting'></i>
    </a>";

    echo "    <!-- Popup content -->
    <div class='popup-content'>
        <p id='popup-comment'>Loading comment...</p>
    </div>";
echo "</div>";

echo "<div style='text-align: center; margin-top: 20px;padding: 10px;'>";
 
 echo "<a href='#' id='preview-link' class='preview-button' style='display: inline-flex; text-decoration: none; width: 40px; height: 40px; border-radius: 50%; background-color: #f0f0f0; align-items: center; justify-content: center; color: #3385ff;'> <i class='fa fa-eye'></i></a>";
echo "</div>";



        echo "</div>";
    } else {
        echo "Memo not found.";
    }
    
    // Close statement and connection
    //$stmt->close();
    $conn->close();
    
} else {
    // Redirect to an error page or homepage if 'id' parameter is not provided
    header("Location: error.php");
    exit();
}

?>

<!-- Your existing HTML code -->
<div id="myModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>

    <!-- Dropdown to select either "Recommend" or "Approve" -->
    <label for="actionSelect">Action:</label>
    <select id="actionSelect" class="action-select">
      <option value="approved">Approve</option>
      <option value="Recommended">Recommend</option>
    </select>

    <!-- Textarea for adding comments -->
    <textarea id="commentInput" class="comment-input" rows="4" placeholder="Add comment"></textarea>
    
    <!-- Button to submit the selected action -->
    <button id="sendCommentButton" class="send-comment-button">Submit</button>
  </div>
</div>

<div id="myModalr" class="modalr">
  <div class="modal-content">
    <span class="close">&times;</span>
    <textarea id="commentInputr" class="comment-input" rows="4" placeholder="Add comment"></textarea>
    <button id="sendCommentButtonr" class="send-comment-button" style = "background-color: red;">Reject</button>
    <button id="reverse_memo" class="send-reverse-comment-button" style = "background-color: blue;">Reverse</button>
  </div>
</div>

<!-- The Modal -->
<div id="documentModal" class="modal" style="display: none;">
  <div class="modal-content">
    <span class="closedoc">&times;</span>
    <iframe id="doc-preview" src="" style="width: 100%; height: 500px;" frameborder="0"></iframe>
  </div>
</div>

<!-- Your existing HTML code -->
<div id="myModaladd" class="modaladd">
  <div class="modal-content">
    <span class="close">&times;</span>
    <div class="form-header">
    <div id="dropdown-container">
        <label for="through">UFS</label>
        <select id="through" name="through[]" required>
            <option value="">Select Position</option>
            <?php
            include 'include.php';

            if (isset($_SESSION['username'])) {
                $currentPositionName = $_SESSION['username'];
                // Modify the SQL query to include the `name` field
                $sql = "SELECT p.Position_name, e.name, e.username 
                        FROM position p 
                        LEFT JOIN employee_access e 
                        ON p.position_id = e.position_id 
                        WHERE p.Position_name != '$currentPositionName'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Display both Position_name and name
                        echo "<option value='" . $row["username"] . "'>" . 
                             $row["username"] . " - " . 
                             (isset($row["name"]) ? $row["name"] : 'No name') ." : " . 
                             (isset($row["username"]) ? $row["username"] : 'No Username') .  
                             "</option>";
                    }
                } else {
                    echo "<option value=''>No positions found</option>";
                }
            }
            $conn->close();
            ?>
        </select>
        <!--a href="#" class="dropdown-toggle">
            <i class="fa fa-plus-circle"></i>
        </a-->
    </div>
</div>
    <input type="hidden" id="session-username" value="<?php echo $_SESSION['username']; ?>">
    <!-- Include the memo_id in a hidden input field -->
    <input type="hidden" id="session-memo-id" value="<?php echo htmlspecialchars($memo_id); ?>">
    <textarea id="commentInputadd" class="comment-input" rows="4" placeholder="Add comment"></textarea>
    <button id="sendCommentButtonadd" class="send-comment-button">Add</button>
  </div>
</div>






<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Show modal when approve button is clicked
    $(".approve-button").click(function() {
        $("#myModal").css("display", "block");
    });

    // Send comment when the send button is clicked
    $("#sendCommentButton").click(function() {
        var memoId = $(".approve-button").data("memo-id");
        var comment = $("#commentInput").val();
        var action = $("#actionSelect").val(); // Get the selected action

        // Send AJAX request to approve.php with memo_id, comment, and action
        $.ajax({
            type: "POST",
            url: "approve.php",
            data: { memo_id: memoId, comment: comment, action: action },
            success: function(response) {
                if (response.status === "action_taken") {
                    // Show SweetAlert for action already taken
                    Swal.fire({
                        icon: 'error',
                        title: 'No action allowed',
                        text: 'Action already taken by this Position Name for this Memo'
                    }).then(function () {
                        window.location.href = 'dashboard.php';
                    });
                    $("#myModal").css("display", "none");
                } else if (response.status === "success") {
                    // Handle success response
                    Swal.fire({
                        icon: 'success',
                        title: 'Memo has been ' + action + '.',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function () {
                        window.location.href = 'dashboard.php';
                    });
                    $("#myModal").css("display", "none");
                } else {
                    // Handle unexpected error response
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An unexpected error occurred. Please try again.'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText); // Log error to console
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error occurred. Please try again.'
                });
            }
        });
    });

    // Close modal when the close button is clicked
    $(".close").click(function() {
        $("#myModal").css("display", "none");
    });

    // Close modal when clicking outside the modal
    $(window).click(function(event) {
        if (event.target == $("#myModal")[0]) {
            $("#myModal").css("display", "none");
        }
    });
});


// JavaScript to handle clicking on the reject button
$(".reject-button").click(function() {
    // Show modal
    $("#myModalr").css("display", "block");
});

// Close modal when clicking the close button
$(".close").click(function() {
    $("#myModalr").css("display", "none");
});

// Close modal when clicking outside the modal
$(window).click(function(event) {
    if (event.target == $("#myModalr")[0]) {
        $("#myModalr").css("display", "none");
    }
});

// Send comment when clicking send button
$("#sendCommentButtonr").click(function() {
    var memoId = $(".reject-button").data("memo-idr");
    var comment = $("#commentInputr").val();
    // Send AJAX request to reject.php with memoId and comment
    $.ajax({
        type: "POST",
        url: "reject.php", // Changed URL to match reject_imprest.php
        data: { memo_id: memoId, comment: comment }, // Changed data field to match the expected fields
        success: function(response) {
            if (response.status === "action_taken") {
                // Show SweetAlert for action already taken
                Swal.fire({
                    icon: 'error',
                    title: 'No action allowed',
                    text: 'Action already taken by this Position Name for this memo'
                }).then(function () {
                    window.location.href = 'dashboard.php';
                });
                // Close modal after sending comment
                $("#myModalr").css("display", "none");
            } else if (response.status === "success") {
                // Handle success response
                Swal.fire({
                    icon: 'success',
                    title: 'Memo has been Rejected.',
                    showConfirmButton: false,
                    timer: 1500
                }).then(function () {
                    window.location.href = 'dashboard.php';
                });
                // Close modal after sending comment
                $("#myModalr").css("display", "none");
            } else {
                // Handle unexpected error response
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred. Please try again.'
                });
            }
        },
        error: function(xhr, status, error) {
            // Handle error response
            console.error(xhr.responseText); // Log error to console
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error occurred. Please try again.'
            });
        }
    });
});



$(document).ready(function() {
    // Show modal when add approval button is clicked
    $(".add_approval-button").click(function() {
        // Show the modal
        $("#myModaladd").css("display", "block");

        // Retrieve memo_id from the hidden input field
        var memoId = $("#session-memo-id").val();
        console.log("Memo ID:", memoId); // Debugging: log the memo ID

        // Store memoId in a data attribute for later use
        $("#sendCommentButtonadd").data("memo-id", memoId);
    });

    // Send comment when the send button is clicked
    $("#sendCommentButtonadd").click(function() {
        // Retrieve the memoId from the data attribute
        var memoId = $(this).data("memo-id");
        var comment = $("#commentInputadd").val();
        var username = $("#through").val(); // Get the selected username from the dropdown

        // Get the session username from a hidden input or sessionStorage (set this up in your PHP)
        var addedBy = $("#session-username").val(); // Or use sessionStorage/session cookie

        // Check if memoId is valid before sending the AJAX request
        if (!memoId) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Memo ID is missing. Please try again.'
            });
            return;
        }

        // Send AJAX request to add_through.php with memo_id, comment, username, and added_by
        $.ajax({
            type: "POST",
            url: "add_through.php",
            data: { memo_id: memoId, comment: comment, username: username, added_by: addedBy },
            dataType: 'json', // Ensure the response is parsed as JSON
            success: function(response) {
                if (response.status === "action_taken") {
                    // Show SweetAlert for action already taken
                    Swal.fire({
                        icon: 'error',
                        title: 'No action allowed',
                        text: 'Action already taken by this Position Name for this Memo'
                    }).then(function () {
                        window.location.href = 'dashboard.php';
                    });
                } else if (response.status === "success") {
                    // Handle success response
                    Swal.fire({
                        icon: 'success',
                        title: 'Approval Added in a queue',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function () {
                        window.location.href = 'dashboard.php';
                    });
                } else {
                    // Handle unexpected error response
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An unexpected error occurred. Please try again.'
                    });
                }

                // Close modal after handling response
                $("#myModaladd").css("display", "none");
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error("AJAX Error:", xhr.responseText); // Log error to console
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error occurred. Please try again.'
                });
            }
        });
    });

    // Close modal when close button is clicked
    $(".close").click(function() {
        $("#myModaladd").css("display", "none");
    });

    // Close modal when clicking outside the modal
    $(window).click(function(event) {
        if (event.target == $("#myModaladd")[0]) {
            $("#myModaladd").css("display", "none");
        }
    });
});


</script>
<script>
$(document).ready(function() {
    var throughCount = 1; // Initial count of through fields

    $('.dropdown-toggle').click(function(e) {
        e.preventDefault();

        // Increment the count and create a new dropdown
        throughCount++;
        var newLabel = $('<label>').attr('for', 'through' + throughCount).text('UFS');
        var newSelect = $('<select>').attr('id', 'through' + throughCount).attr('name', 'through[]').attr('required', true);
        
        // Add a minus icon to remove the dropdown
        var removeIcon = $('<a href="#" class="remove-dropdown"><i class="fa fa-minus-circle"></i></a>');

        // Fetch data using AJAX to populate the dropdown
        $.ajax({
            url: 'fetch_select.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                // Clear previous options
                newSelect.empty();

                // Add a default option
                newSelect.append($('<option>').text('Select Position').attr('value', ''));

                // Populate the dropdown with Position_name and name
                $.each(response, function(index, item) {
                    var optionText = item.username + ' - ' + item.name + ' : ' + item.Position_name;
                    var option = $('<option>').attr('value', item.username).text(optionText);
                    newSelect.append(option);
                });
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error);
            }
        });

        // Create a container for the label, select, and remove icon
        var dropdownGroup = $('<div class="dropdown-group"></div>');
        dropdownGroup.append(newLabel);
        dropdownGroup.append(newSelect);
        dropdownGroup.append(removeIcon);

        // Append the new group to the container
        $('#dropdown-container').append(dropdownGroup);
    });

    // Event delegation to handle the removal of dynamically added dropdowns
    $('#dropdown-container').on('click', '.remove-dropdown', function(e) {
        e.preventDefault();
        $(this).closest('.dropdown-group').remove(); // Remove the entire group (label, select, and icon)
    });
});
</script>
<script>
    // Store a value
localStorage.setItem('key', 'value');

// Store an object (convert to JSON string)
const pageDetails = {
  title: document.title,
  url: window.location.href
};
localStorage.setItem('pageDetails', JSON.stringify(pageDetails));

</script>
<script>
    // Get a value
const value = localStorage.getItem('key');

// Get an object (parse JSON string)
const savedPageDetails = JSON.parse(localStorage.getItem('pageDetails'));
console.log(savedPageDetails);

</script>
<script>
    // Remove a specific item
localStorage.removeItem('key');

// Clear all items
localStorage.clear();

</script>
<script>
$(document).ready(function() {
    $('.popup-trigger').hover(function() {
        $(this).next('.popup-content').stop(true, true).fadeIn();
    }, function() {
        $(this).next('.popup-content').stop(true, true).fadeOut();
    });
});

</script>
<script>
    $(document).ready(function() {
    // Function to fetch and display the comment
    function fetchComment(memoId) {
        $.ajax({
            url: 'fetch_comments.php',
            type: 'GET',
            data: { memo_id: memoId },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#popup-comment').text(response.comment);
                } else {
                    $('#popup-comment').text('No comment found.');
                }
            },
            error: function() {
                $('#popup-comment').text('Error fetching comment.');
            }
        });
    }

    // Show modal when add approval button is clicked
    $(".popup-trigger").hover(function() {
        var memoId = $(this).data('memo-id');
        fetchComment(memoId); // Fetch and display the comment
    });

    // Close modal when clicking outside the modal
    $(window).click(function(event) {
        if (event.target == $(".popup-trigger")[0]) {
            $(".popup-content").css("display", "none");
        }
    });
});

</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const cells = document.querySelectorAll('.comment-cell');

    cells.forEach(function(cell) {
        cell.addEventListener('mouseenter', function() {
            this.textContent = this.getAttribute('data-full-comment');
        });

        cell.addEventListener('mouseleave', function() {
            this.textContent = this.getAttribute('data-full-comment').substring(0, 2) + '...';
        });
    });
});

</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('preview-link').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default link behavior

        // Get the memo ID from the URL or other source
        var memoId = <?php echo json_encode($memo_id); ?>;

        // Create a new AJAX request
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetch_file_path.php?id=' + memoId, true);

        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 400) {
                // Success
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Get the file path and extension
                    var filePath = response.file_path;
                    var fileExtension = filePath.split('.').pop().toLowerCase();

                    // List of previewable file types
                    var previewableTypes = ['pdf', 'jpg', 'jpeg', 'png', 'gif'];

                    if (previewableTypes.includes(fileExtension)) {
                        // Set the iframe source to the file path
                        var iframe = document.getElementById('doc-preview');
                        iframe.src = filePath;

                        // Show the modal
                        var modal = document.getElementById('documentModal');
                        modal.style.display = 'block';
                    } else {
                        // If file type is not previewable, download the file and show a SweetAlert success message
                        var link = document.createElement('a');
                        link.href = filePath;
                        link.download = filePath.split('/').pop(); // Extract the filename
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);

                        // Show SweetAlert success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Download Complete',
                            text: 'Your file has been downloaded successfully. Please check your downloads.',
                        });
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Not Found',
                        text: 'The file you are trying to access does not exist.',
                    });
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'There was an error fetching the file path.',
                });
            }
        };

        xhr.onerror = function() {
            Swal.fire({
                icon: 'error',
                title: 'Request Failed',
                text: 'The request to fetch the file path failed.',
            });
        };

        xhr.send();
    });

    // Close the modal when the user clicks on the close button
    document.querySelector('.closedoc').addEventListener('click', function() {
        var modal = document.getElementById('documentModal');
        modal.style.display = 'none';
    });

    // Close the modal when the user clicks outside of the modal content
    window.addEventListener('click', function(event) {
        var modal = document.getElementById('documentModal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});

</script>


<?php include 'footer.php'; ?>
</body>
</html>
