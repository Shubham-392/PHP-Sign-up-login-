<?php
declare(strict_types=1); // strict requirement
session_start(); // Start session to track user
include("db.php");

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user inputs
    $name = sanitize_input($con, $_POST['name']);
    $email = sanitize_input($con, $_POST['email']);
    $password = $_POST["password"]; // Don't sanitize password before hashing
    $confirmPassword = $_POST["confirm_password"];
    
    // Validation
    $errors = [];
    
    // Validate name
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    // Validate email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    } else {
        // Check if email already exists
        $stmt = $con->prepare("SELECT id FROM Persons WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $errors[] = "Email already exists. Please use a different email or login.";
        }
    }
    
    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters";
    }
    
    // Validate password confirmation
    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match";
    }
    
    // If validation passes, proceed with registration
    if (empty($errors)) {
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new user
        $stmt = $con->prepare("INSERT INTO Persons (name, email, password, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sss", $name, $email, $hashedPassword);
        
        if ($stmt->execute()) {
            // Registration successful
            $_SESSION['registration_success'] = true;
            header("Location: site.php");
            exit;
        } else {
            // Registration failed
            $errors[] = "Registration failed: " . $con->error;
        }
    }
    
    // If we got here, there were errors
    $_SESSION['signup_errors'] = $errors;
    header("Location: site.php");
    exit;
}

// If someone tries to access this page directly without POST data
header("Location: site.php");
exit;
?>