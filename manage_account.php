<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="x-icon" href="KCBLLOGO.PNG">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Account</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            padding-top: 100px; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgba(0,0,0,0.5); 
        }

        .modal-content {
            background-color: #fff;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            animation: animatetop 0.4s;
        }

        @keyframes animatetop {
            from {top: -300px; opacity: 0} 
            to {top: 0; opacity: 1}
        }

        .closec {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .closec:hover,
        .closec:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .container {
            padding: 16px;
        }

        h2 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        @media (max-width: 768px) {
            .modal-content {
                width: 90%;
                padding: 10px;
            }

            h2 {
                font-size: 20px;
                margin-bottom: 15px;
            }

            input[type="text"],
            input[type="password"],
            input[type="submit"] {
                padding: 10px 15px;
                margin: 5px 0;
            }

            .closec {
                font-size: 24px;
            }
        }

        @media (max-width: 480px) {
            .modal-content {
                width: 95%;
                padding: 8px;
            }

            h2 {
                font-size: 18px;
                margin-bottom: 10px;
            }

            input[type="text"],
            input[type="password"],
            input[type="submit"] {
                padding: 8px 12px;
                margin: 3px 0;
            }

            .closec {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
<?php
session_start();
include 'include.php'; // Include your database connection script

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate session
    if (!isset($_SESSION['username'])) {
        header("Location: login.php"); // Redirect to login if session username is not set
        exit();
    }

    // Fetch username from session
    $username = $_SESSION['username'];

    // Fetch and validate new password and confirm password
    $newPassword = $_POST["new_password"];
    $confirmPassword = $_POST["confirm_password"];

    if ($newPassword !== $confirmPassword) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Passwords do not match. Please try again.'
                });
              </script>";
        exit();
    }

    // Hash the new password securely
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Fetch id from employee_access table based on username
    $sql_select_id = "SELECT id FROM employee_access WHERE name = ?";
    $stmt_select_id = $conn->prepare($sql_select_id);

    if ($stmt_select_id) {
        $stmt_select_id->bind_param("s", $username);
        $stmt_select_id->execute();
        $stmt_select_id->bind_result($id);
        $stmt_select_id->fetch();
        $stmt_select_id->close();

        // Show the fetched ID for verification
        //echo "Fetched id: " . htmlspecialchars($id) . "<br>";

        // Prepare and execute the update query
        $sql_update_password = "UPDATE employee_access SET password = ? WHERE id = ?";
        $stmt_update_password = $conn->prepare($sql_update_password);

        if ($stmt_update_password) {
            $stmt_update_password->bind_param("si", $hashedPassword, $id);
            if ($stmt_update_password->execute()) {
                // Check if the update was successful
                if ($stmt_update_password->affected_rows === 1) {
                    echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Hello!, $username Your Password updated successfully'
                            }).then(function () {
                                window.location.href = 'dashboard.php';
                            });
                          </script>";
                    exit();
                } else {
                    echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed to update password. Please try again later.'
                            }).then(function () {
                                window.location.href = 'dashboard.php';
                            });
                          </script>";
                    exit();
                }
            } else {
                echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Execution failed. Please try again later.',
                            text: 'Error: " . $stmt_update_password->error . "'
                        });
                      </script>";
                exit();
            }

            $stmt_update_password->close();
        } else {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed to prepare statement',
                        text: 'Error: " . $conn->error . "'
                    });
                  </script>";
            exit();
        }
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Failed to fetch user ID',
                    text: 'Error: " . $conn->error . "'
                });
              </script>";
        exit();
    }

    $conn->close();
} else {
    // Redirect to appropriate page if accessed directly without POST request
    header("Location: dashboard.php");
    exit();
}
?>

<!-- Modal for managing account -->
<div id="manageAccountModal" class="modal">
    <div class="modal-content">
        <span class="closec">&times;</span>
        <div class="container">
            
            <form id="updateAccountForm" method="POST" action="">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" readonly value="<?php echo htmlspecialchars($username); ?>"><br><br>

                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required><br><br>

                

                <input type="submit" value="Update Password">
            </form>
        </div>
    </div>
</div>
</body>
</html>
