<?php
// Development mode - enable errors (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database credentials
// In production, store these in a separate config file outside web root
$DATABASE_HOST = "localhost";
$DATABASE_USER = "root";
$DATABASE_PASS = "Shubham@392";
$DATABASE_NAME = "phpsignup";

// Try and connect using the info above
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    // If there is an error with the connection, stop the script and display the error.
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Add this function to escape user input to prevent SQL injection
function sanitize_input($con, $input) {
    if (is_array($input)) {
        $output = array();
        foreach ($input as $key => $value) {
            $output[$key] = sanitize_input($con, $value);
        }
        return $output;
    } else {
        return mysqli_real_escape_string($con, trim($input));
    }
}
?>