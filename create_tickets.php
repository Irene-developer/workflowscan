<?php
session_start();
include("include.php");

// Initialize variables with default values
$subject = $tt = $priority = $ticket = $st = "";
$pdate = date('Y-m-d');
$email = isset($_SESSION['login']) ? $_SESSION['login'] : '';

// Check if the form is submitted
if (isset($_POST['send'])) {
    // File counter handling
    $count_my_page = "hitcounter.txt";
    if (file_exists($count_my_page)) {
        $hits = file($count_my_page);
        $hits[0]++;
        $fp = fopen($count_my_page, "w");
        fputs($fp, "$hits[0]");
        fclose($fp);
        $tid = $hits[0];
    } else {
        die("Error: Counter file does not exist.");
    }

    // Get form values if they exist
    $subject = isset($_POST['subject']) ? mysqli_real_escape_string($conn, $_POST['subject']) : '';
    $tt = isset($_POST['tasktype']) ? mysqli_real_escape_string($conn, $_POST['tasktype']) : '';
    $priority = isset($_POST['priority']) ? mysqli_real_escape_string($conn, $_POST['priority']) : '';
    $ticket = isset($_POST['description']) ? mysqli_real_escape_string($conn, $_POST['description']) : '';
    $st = "Open";

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO ticket (ticket_id, email_id, subject, task_type, priority, ticket, status, posting_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssss", $tid, $email, $subject, $tt, $priority, $ticket, $st, $pdate);

    if ($stmt->execute()) {
        echo "<script>alert('Ticket Generated');</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <title>CRM | Create Ticket</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <link rel="stylesheet" href="stylestickets.css">
    <link href="assets/css/responsive.css" rel="stylesheet" type="text/css"/>
</head>
<body>
    <div class="page-content"> 
        <div class="content">  
            <div class="page-title">    
                <div class="row">
                    <div class="col-md-12">
                        <form class="form-horizontal" name="form1" method="post" action="" onSubmit="return valid();">
                            <div class="panel panel-default">
                                <div class="panel-body">                                                                        
                                    <p align="center" style="color:#FF0000"><?=$_SESSION['msg1'];?><?=$_SESSION['msg1']="";?></p>
                                    <div class="form-group">                                        
                                        <label class="col-md-3 col-xs-12 control-label">Subject</label>
                                        <div class="col-md-6 col-xs-12">
                                            <div class="input-group">
                                                <input type="text" name="subject" id="subject" value="<?= htmlspecialchars($subject); ?>" required class="form-control"/>
                                            </div>            
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Task Type</label>
                                        <div class="col-md-6 col-xs-12">                                                                                            
                                            <select name="tasktype" class="form-control select" required>
                                                <option value="">Select your Task Type</option>
                                                <option value="billing" <?= $tt == 'billing' ? 'selected' : ''; ?>>Create</option>
                                                <option value="ot1" <?= $tt == 'ot1' ? 'selected' : ''; ?>>Disable</option>
                                                <option value="ot2" <?= $tt == 'ot2' ? 'selected' : ''; ?>>Enable</option>
                                                <option value="ot3" <?= $tt == 'ot3' ? 'selected' : ''; ?>>Remove</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Priority</label>
                                        <div class="col-md-6 col-xs-12">                                                                                            
                                            <select name="priority" class="form-control select">
                                                <option value="">Choose your Priority</option>
                                                <option value="important" <?= $priority == 'important' ? 'selected' : ''; ?>>Important</option>
                                                <option value="urgent(functional problem)" <?= $priority == 'urgent(functional problem)' ? 'selected' : ''; ?>>Urgent (Functional Problem)</option>
                                                <option value="non-urgent" <?= $priority == 'non-urgent' ? 'selected' : ''; ?>>Non-Urgent</option>
                                                <option value="question" <?= $priority == 'question' ? 'selected' : ''; ?>>Question</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Description</label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <textarea name="description" required class="form-control" rows="5"><?= htmlspecialchars($ticket); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="panel-footer" style="display: flex; justify-content: space-between;">   
                                    <input type="submit" value="Send" name="send" class="btn btn-primary pull-right" style="max-width: 100px; margin-left: 15px;">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>                    
            </div>
        </div>
    </div>

    <script src="assets/plugins/jquery-1.8.3.min.js" type="text/javascript"></script> 
    <script src="assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script> 
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> 
    <script src="assets/plugins/breakpoints.js" type="text/javascript"></script> 
    <script src="assets/plugins/jquery-unveil/jquery.unveil.min.js" type="text/javascript"></script> 
    <script src="assets/plugins/jquery-block-ui/jqueryblockui.js" type="text/javascript"></script> 
    <script src="assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js" type="text/javascript"></script>
    <script src="assets/plugins/pace/pace.min.js" type="text/javascript"></script>  
    <script src="assets/plugins/jquery-numberAnimate/jquery.animateNumbers.js" type="text/javascript"></script>
    <script src="assets/js/core.js" type="text/javascript"></script> 
    <script src="assets/js/chat.js" type="text/javascript"></script> 
    <script src="assets/js/demo.js" type="text/javascript"></script> 
</body>
</html>
