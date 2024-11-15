<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ICT Head Page</title>
<style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #f5f5f5;
    }
</style>
</head>
<body>

<h2>Access Requests</h2>

<table border='1'>
<tr>
    <th>Name</th>
    <th>Request Type</th>
    <th>Designation</th>
    <th>Branch/HQ</th>
    <th>System Name</th>
    <th>AS Role</th>
    <th>Justification</th>
    <th>Date</th>
    <th>Supervisor Action</th>
    <th>Supervisor Justification</th>
    <th>Supervised by</th>
</tr>

<?php include 'display_data_icthead.php'; ?>
</table>

</body>
</html>
