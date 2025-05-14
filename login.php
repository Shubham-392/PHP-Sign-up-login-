<?php
declare(strict_types=1); // strict requirement
session_start(); // Start session to track logged in user
include("db.php");

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user inputs
    $userEmail = sanitize_input($con, $_POST['email']);
    $password = $_POST["password"]; // Don't sanitize password before verification
    
    // Validation
    $errors = [];
    
    // Validate email
    if (empty($userEmail) || !filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    
    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required";
    }
    
    // If validation passes, proceed with login
    if (empty($errors)) {
        // Get user from database
        $stmt = $con->prepare("SELECT id, name, email, password FROM Persons WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $userEmail);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // User exists
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct, set session variables
                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                
                // Redirect to dashboard
                header("Location: http://gitdiagram.com");
                exit;
            } else {
                // Password is incorrect
                $errors[] = "Invalid email or password";
            }
        } else {
            // User doesn't exist
            $errors[] = "Invalid email or password";
        }
    }
    
    // If we got here, there were errors
    $_SESSION['login_errors'] = $errors;
    header("Location: site.php");
    exit;
}

// If someone tries to access this page directly without POST data
header("Location: site.php");
exit;
?>