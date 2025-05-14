<?php
session_start(); // Start session

// Check for messages
$loginErrors = $_SESSION['login_errors'] ?? [];
$signupErrors = $_SESSION['signup_errors'] ?? [];
$registrationSuccess = $_SESSION['registration_success'] ?? false;

// Clear messages after displaying them
unset($_SESSION['login_errors']);
unset($_SESSION['signup_errors']);
unset($_SESSION['registration_success']);

// Determine which tab to show (based on errors)
$activeTab = "login";
if (!empty($signupErrors) || $registrationSuccess) {
    $activeTab = "signup";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GitDiagram - Login/Signup</title>
    <link rel = "stylesheet" href = "css/style.css">
</head>
<body>
    <nav class="navbar">
        <a href="#" class="logo">Git<span class = "diagram" >Diagram</span></a>
    </nav>

    <div class="auth-container">
        <div class="card">
            <div class="tab-container">
                <div class="tab <?php echo $activeTab === 'login' ? 'active' : ''; ?>" id="login-tab">Login</div>
                <div class="tab <?php echo $activeTab === 'signup' ? 'active' : ''; ?>" id="signup-tab">Sign Up</div>
            </div>

            <!-- Login Form -->
            <form action="login.php" method="POST" class="login-form form-content" style="display: <?php echo $activeTab === 'login' ? 'block' : 'none'; ?>">
                <?php if (!empty($loginErrors)): ?>
                    <div class="error-container">
                        <strong>Login failed:</strong>
                        <ul>
                            <?php foreach ($loginErrors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="login-email">Email</label>
                    <input type="email" id="login-email" name="email" required autocomplete="email">
                </div>
                <div class="form-group">
                    <label for="login-password">Password</label>
                    <input type="password" id="login-password" name="password" required autocomplete="current-password">
                    <span class="helper-text">
                        <a href="forgot-password.php">Forgot password?</a>
                    </span>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>

            <!-- Sign Up Form -->
            <form action="signup.php" method="POST" class="signup-form form-content" style="display: <?php echo $activeTab === 'signup' ? 'block' : 'none'; ?>">
                <?php if ($registrationSuccess): ?>
                    <div class="success-message">
                        Registration successful! You can now log in with your credentials.
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($signupErrors)): ?>
                    <div class="error-container">
                        <strong>Registration failed:</strong>
                        <ul>
                            <?php foreach ($signupErrors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="signup-name">Full Name</label>
                    <input type="text" id="signup-name" name="name" required autocomplete="name">
                </div>
                <div class="form-group">
                    <label for="signup-email">Email</label>
                    <input type="email" id="signup-email" name="email" required autocomplete="email">
                </div>
                <div class="form-group">
                    <label for="signup-password">Password</label>
                    <input type="password" id="signup-password" name="password" required autocomplete="new-password">
                    <span class="helper-text">Password must be at least 8 characters</span>
                </div>
                <div class="form-group">
                    <label for="signup-confirm">Confirm Password</label>
                    <input type="password" id="signup-confirm" name="confirm_password" required autocomplete="new-password">
                </div>
                <button type="submit" class="btn">Create Account</button>
            </form>
        </div>
    </div>

    <footer>
        &copy; 2025 GitDiagram. All rights reserved.
    </footer>

    <script>
        // This script toggles between login and signup forms
        const loginTab = document.getElementById('login-tab');
        const signupTab = document.getElementById('signup-tab');
        const loginForm = document.querySelector('.login-form');
        const signupForm = document.querySelector('.signup-form');

        loginTab.addEventListener('click', function() {
            loginTab.classList.add('active');
            signupTab.classList.remove('active');
            loginForm.style.display = 'block';
            signupForm.style.display = 'none';
        });

        signupTab.addEventListener('click', function() {
            signupTab.classList.add('active');
            loginTab.classList.remove('active');
            signupForm.style.display = 'block';
            loginForm.style.display = 'none';
        });
    </script>
</body>
</html>