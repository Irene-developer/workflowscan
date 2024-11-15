<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="x-icon" href="KCBLLOGO.PNG">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="assets/css/responsive.css" rel="stylesheet" type="text/css"/>
      <link rel="stylesheet" href="assets/sweetalert2/sweetalert2.min.css">
      <script src="assets/sweetalert2/sweetalert2.all.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 320px;
        }

        .login-container h2 {
            margin-bottom: 20px;
            text-align: center;
            margin-top: 100px;
        }

        .login-container form {
            display: flex;
            flex-direction: column;
        }

        .login-container form label {
            margin-bottom: 8px;
        }

        .login-container form input[type="text"],
        .login-container form input[type="password"] {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .login-container form button {
            padding: 12px;
            background-color: #3385ff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .login-container form button:hover {
            background-color: #1e70bf;
        }

        .container-appearance {
            display: flex;
            align-content: center;
            justify-content: space-between;
            width: 100%;
            height: 100%;
        }

        .image-animate {
            display: flex;
            align-items: center;
            justify-content: center;
            align-content: center;
            margin-left: 400px;
            margin-bottom: 200px;
        }

        .image-animate img {
            max-width: 400px;
            animation: logoAnimation 2s ease infinite;
        }

        @keyframes logoAnimation {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }

        .error-container {
            background-color: red;
            color: #fff;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            display: none;
            width: 300px;
        }

        .toast-success {
            background-color: #5cb85c;
            color: #fff;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            display: none;
            width: 300px;
        }
    </style>
      <link rel="stylesheet" href="assets/sweetalert2/sweetalert2.min.css">
   <script src="assets/sweetalert2/sweetalert2.all.min.js"></script>
</head>
<body>
    <div class="container-appearance">
        <div class="image-animate">
            <img src="KCBLLOGO.png">
        </div>
        <div class="login-container">
            <h2>Login</h2>
            <div class="error-container" id="error-message"></div>
            <form id="login-form" action="process_login.php" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                
                <button type="submit">Login</button>
            </form>
            <div class="toast-success" id="toastSuccess">
                <p><i class="fas fa-check" style="color: white;"></i> Login Successfully</p>
            </div>
        </div>
    </div>

 <script src="assets/js/jquery/jquery-3.7.1.min.js"></script>
  <link rel="stylesheet" href="assets/sweetalert2/sweetalert2.min.css">
   <script src="assets/sweetalert2/sweetalert2.all.min.js"></script>
      <link rel="stylesheet" href="assets/fas fas fas 3/css/font-awesome.min.css"><!-- Add SweetAlert2 library if not included -->

<script>
    document.getElementById('login-form').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission
        var form = this;
        var formData = new FormData(form); // Serialize form data

        // Send form data via AJAX
        var xhr = new XMLHttpRequest();
        xhr.open(form.method, form.action, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.timeout = 10000; // Set timeout to 10 seconds

        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        Swal.fire({
                            title: 'Welcome ' + response.username,
                            icon: 'success',
                            confirmButtonText: 'Continue',
                            showCancelButton: true,
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                function promptForDomainPassword() {
                                    Swal.fire({
                                        title: 'Domain Password',
                                        input: 'password',
                                        inputLabel: 'Enter your domain password',
                                        inputPlaceholder: 'Password',
                                        inputAttributes: {
                                            autocapitalize: 'off',
                                            autocorrect: 'off'
                                        },
                                        showCancelButton: true,
                                        confirmButtonText: 'Submit',
                                        cancelButtonText: 'Cancel',
                                        showLoaderOnConfirm: true,
                                        preConfirm: (password) => {
                                            return new Promise((resolve, reject) => {
                                                // Send domain password for verification
                                                var xhr = new XMLHttpRequest();
                                                xhr.open('POST', 'process_domain_auth.php', true);
                                                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                                                xhr.timeout = 10000; // Set timeout to 10 seconds

                                                xhr.onreadystatechange = function() {
                                                    if (xhr.readyState === XMLHttpRequest.DONE) {
                                                        if (xhr.status === 200) {
                                                            var response = JSON.parse(xhr.responseText);
                                                            if (response.success) {
                                                                resolve(); // Resolve the promise to proceed
                                                            } else {
                                                                Swal.showValidationMessage(
                                                                    `Domain Authentication Failed: ${response.message}`
                                                                );
                                                                // Delay for 3 seconds before allowing the user to try again
                                                                setTimeout(() => {
                                                                    promptForDomainPassword();
                                                                }, 3000);
                                                            }
                                                        } else {
                                                            Swal.showValidationMessage(
                                                                'Request failed. Please try again.'
                                                            );
                                                        }
                                                    }
                                                };

                                                xhr.ontimeout = function () {
                                                    Swal.showValidationMessage(
                                                        'Request timed out. Please try again.'
                                                    );
                                                };

                                                xhr.send('username=' + response.username + '&password=' + password);
                                            });
                                        },
                                        allowOutsideClick: () => !Swal.isLoading()
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            // Redirect to dashboard
                                            window.location.href = "dashboard.php";
                                        } else if (result.isDismissed) {
                                            console.log('Domain password prompt was canceled.');
                                        }
                                    });
                                }

                                promptForDomainPassword();
                            } else if (result.isDismissed) {
                                console.log('Welcome prompt was canceled.');
                            }
                        });
                    } else {
                        document.getElementById('error-message').textContent = response.message;
                        document.getElementById('error-message').style.display = 'block';
                        setTimeout(function() {
                            document.getElementById('error-message').style.display = 'none';
                        }, 3000);
                    }
                } else {
                    console.error('Login request failed: ', xhr.status);
                }
            }
        };

        xhr.ontimeout = function () {
            document.getElementById('error-message').textContent = 'Request timed out. Please try again.';
            document.getElementById('error-message').style.display = 'block';
            setTimeout(function() {
                document.getElementById('error-message').style.display = 'none';
            }, 3000);
        };

        xhr.send(formData);
    });
</script>


</body>
</html>