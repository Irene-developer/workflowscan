<?php
// process_domain_auth.php

// Start session
session_start();

// LDAP server configuration
$ldap_host = '192.168.10.50'; // LDAP server address
$ldap_port = 389; // Default LDAP port
$ldap_dn = "DC=kcbltz,DC=crdbbankplc,DC=com"; // Base DN
$ldap_domain = "kcbltz.crdbbankplc.com"; // Domain name

// Response array
$response = array('success' => false, 'message' => '');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["username"]) && isset($_POST["password"]) && !empty($_POST["username"]) && !empty($_POST["password"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];

        // Connect to LDAP server
        $ldap_conn = ldap_connect($ldap_host, $ldap_port);
        if ($ldap_conn) {
            ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldap_conn, LDAP_OPT_REFERRALS, 0);

            // Bind to LDAP server
            $ldap_rdn = "$username@$ldap_domain";
            $bind = @ldap_bind($ldap_conn, $ldap_rdn, $password);

            if ($bind) {
                // Authentication successful
                $response['success'] = true;
                $response['message'] = "Welcome $username";

                // Save additional information to session if needed
                // e.g., $_SESSION['ldap_user'] = $username;

                ldap_unbind($ldap_conn);
            } else {
                // Authentication failed
                $response['message'] = "Invalid domain credentials.";
            }
        } else {
            $response['message'] = "Could not connect to LDAP server.";
        }
    } else {
        $response['message'] = "Please enter both username and password.";
    }
} else {
    $response['message'] = "Invalid request method.";
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
