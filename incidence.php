<?php 
session_start();

// Assuming $username is set in the session
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

// Later in your HTML code
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" type="x-icon" href="KCBLLOGO.PNG">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risk Event/Incident Reporting Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1300px;
            margin: auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h2, h3 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        input[type="text"], input[type="date"], select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="text"]:focus, input[type="date"]:focus, select:focus {
            outline: none;
            border-color: #6cb2eb;
            box-shadow: 0 0 5px #6cb2eb;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .step-buttons {
            display: flex;
            justify-content: space-between;
        }
        #nextbtn {
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }
        #nextbtn:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }
        .step-buttons-last {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .image-animate {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .image-animate h2 {
            max-width: 400px;
            animation: logoAnimation 2s ease infinite;
            color: #3385ff;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="image-animate">
            <h2>RISK EVENT or INCIDENT REPORTING FORM</h2>
        </div>
        
        <form id="multi-step-form" action="submit_incidence.php" method="post">
            <!-- Step 1: Reporting Person Details -->
            <div id="step-1" class="step">
                <h3 style="color: #4CAF50;">Reporting Person Details</h3>
                <table>
                    <tr>
                        <th>NAME:</th>
                        <td><input type="text" name="name" id="name" value="<?php echo htmlspecialchars($username); ?>" readonly></td>
                    </tr>
                    <tr>
                        <th>BUSINESS UNIT:</th>
                        <td><input type="text" name="business_unit" id="business_unit" required></td>
                    </tr>
                    <tr>
                        <th>PHONE NUMBER:</th>
                        <td><input type="text" name="phone_number" id="phone_number" required></td>
                    </tr>
                    <tr>
                        <th>EMAIL ADDRESS:</th>
                        <td><input type="text" name="email_address" id="email_address" required></td>
                    </tr>
                </table>
                <div class="step-buttons">
                    <button type="button" disabled id="nextbtn">Previous</button>
                    <button type="button" onclick="nextStep(1)" id="nextbtn">Next</button>
                </div>
            </div>
            <!-- Step 2: Event/Incident Details -->
            <div id="step-2" class="step" style="display: none;">
                <h3 style="color: #4CAF50;">Event/Incident Details</h3>
                <table>
                    <tr>
                        <th>UNIT (BRANCH):</th>
                        <td><input type="text" name="branch" id="branch" required></td>
                    </tr>
                    <tr>
                        <th>REPORTING DATE:</th>
                        <td><input type="date" name="reporting_date" id="reporting_date" required></td>
                    </tr>
                    <tr>
                        <th>INCIDENT DATE:</th>
                        <td><input type="date" name="incident_date" id="incident_date" required></td>
                    </tr>
                    <tr>
                        <th>DISCOVERY DATE:</th>
                        <td><input type="date" name="discovery_date" id="discovery_date" required></td>
                    </tr>
                    <tr>
                        <th>IMPACT DATE (From):</th>
                        <td><input type="date" name="impact_date_from" id="impact_date_from" required></td>
                    </tr>
                    <tr>
                        <th>IMPACT DATE (To):</th>
                        <td><input type="date" name="impact_date_to" id="impact_date_to"></td>
                    </tr>
                </table>
                <div class="step-buttons">
                    <button type="button" onclick="prevStep(2)" id="nextbtn">Previous</button>
                    <button type="button" onclick="nextStep(2)" id="nextbtn">Next</button>
                </div>
            </div>
            <!-- Step 3: Event Details -->
            <div id="step-3" class="step" style="display: none;">
                <h3 style="color: #4CAF50;">Event Details</h3>
                <table>
                    <tr>
                        <th>WHAT HAPPENED?</th>
                        <td><input type="text" name="what_happened" id="what_happened" required></td>
                    </tr>
                    <tr>
                        <th>HOW IT HAPPENED?</th>
                        <td><input type="text" name="how_it_happened" id="how_it_happened" required></td>
                    </tr>
                    <tr>
                        <th>WHAT ARE THE IMPACTS TO THE BUSINESS?</th>
                        <td><input type="text" name="impacts_to_business" id="impacts_to_business" required></td>
                    </tr>
                    <tr>
                        <th>WHAT IS THE ROOT CAUSE OF THE INCIDENT?</th>
                        <td>
                            <select name="root_cause_select" id="root_cause_select" onchange="toggleRootCauseInput()">
                                <option value="Unknown">Unknown</option>
                                <option value="Known">Known</option>
                            </select>
                            <input type="text" name="root_cause_input" id="root_cause_input" style="display: none;" placeholder="Explanation">
                        </td>
                    </tr>
                    <tr>
                        <th>WHAT ACTIONS HAVE BEEN TAKEN?</th>
                        <td><input type="text" name="actions_taken" id="actions_taken" required></td>
                    </tr>
                    <tr>
                        <th>WHAT IS THE STATUS (CLOSED/OPEN)?</th>
                        <td>
                            <select name="status" id="status">
                                <option value="Open">Open</option>
                                <option value="Closed">Closed</option>
                                <option value="In Progress">In Progress</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>PRIORITY:</th>
                        <td>
                            <select name="priority" id="priority">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <div class="step-buttons-last">
                    <button type="button" onclick="prevStep(3)" id="nextbtn">Previous</button>
                    <input type="submit" value="Submit" id="nextbtn">
                </div>
            </div>
        </form>
    </div>

    <script>
        function nextStep(currentStep) {
            document.getElementById(`step-${currentStep}`).style.display = 'none';
            document.getElementById(`step-${currentStep + 1}`).style.display = 'block';
        }

        function prevStep(currentStep) {
            document.getElementById(`step-${currentStep}`).style.display = 'none';
            document.getElementById(`step-${currentStep - 1}`).style.display = 'block';
        }

        function toggleRootCauseInput() {
            const rootCauseSelect = document.getElementById('root_cause_select');
            const rootCauseInput = document.getElementById('root_cause_input');
            if (rootCauseSelect.value === 'Known') {
                rootCauseInput.style.display = 'block';
                rootCauseInput.required = true;
            } else {
                rootCauseInput.style.display = 'none';
                rootCauseInput.required = false;
            }
        }
    </script>
</body>
</html>
