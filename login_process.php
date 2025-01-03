<?php
session_start();
include 'config.php';

// Security Headers
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
header("Content-Security-Policy: default-src 'self';");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");

// Secure Session Cookies
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // Enable if using HTTPS

// Rate Limiting: Allow only 5 attempts within 15 minutes
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt_time'] = time();
}
if ($_SESSION['login_attempts'] >= 5 && time() - $_SESSION['last_attempt_time'] < 900) {
    $_SESSION['error'] = "Too many login attempts. Try again later.";
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Secure Connection to Database
    $conn = new mysqli("localhost", "root", "", "student_college_finder");
    
    // Check the connection
    if ($conn->connect_error) {
        error_log("Connection failed: " . $conn->connect_error); // Log error for debugging
        die("An error occurred. Please try again later.");
    }

    // Sanitize Inputs
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email or password.";
        header("Location: login.php");
        exit();
    }

    // Fetch User Details Using Prepared Statement
    $stmt = $conn->prepare("SELECT id, full_name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $fullName, $hashedPassword);
        $stmt->fetch();

        // Verify Password
        if (password_verify($password, $hashedPassword)) {
            // Regenerate Session ID to Prevent Fixation
            session_regenerate_id(true); // true to delete the old session

            // Store User Information in Session
            $_SESSION['user_id'] = $id;
            $_SESSION['full_name'] = $fullName;

            // Reset Login Attempts
            $_SESSION['login_attempts'] = 0;

            // Log admin login in the user_activity table
            $activityType = 'admin_login';
            $activityTime = date('Y-m-d H:i:s');

            $logStmt = $conn->prepare("INSERT INTO user_activity (user_id, activity_type, activity_time) VALUES (?, ?, ?)");
            $logStmt->bind_param("iss", $id, $activityType, $activityTime);
            $logStmt->execute();
            $logStmt->close();

            // Redirect to Dashboard
            header("Location: loading.php");
            exit();
        } else {
            // Invalid Password
            $_SESSION['error'] = "Invalid email or password.";
            $_SESSION['login_attempts']++;
            $_SESSION['last_attempt_time'] = time();
            header("Location: login.php");
            exit();
        }
    } else {
        // User Not Found
        $_SESSION['error'] = "Invalid email or password.";
        $_SESSION['login_attempts']++;
        $_SESSION['last_attempt_time'] = time();
        header("Location: login.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}

// Example for Admin Add User Functionality
if (isset($_POST['add_user'])) {
    // Ensure only admins can add users
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        die("Unauthorized access.");
    }

    // Get user data
    $newUserEmail = filter_var(trim($_POST['new_user_email']), FILTER_SANITIZE_EMAIL);
    $newUserPassword = trim($_POST['new_user_password']);
    $newUserFullName = trim($_POST['new_user_full_name']);

    // Validate password complexity
    if (strlen($newUserPassword) < 8 || !preg_match('/[A-Z]/', $newUserPassword) || !preg_match('/[0-9]/', $newUserPassword)) {
        $_SESSION['error'] = "Password must be at least 8 characters long and include a number and uppercase letter.";
        header("Location: admin_dashboard.php");
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($newUserPassword, PASSWORD_DEFAULT);

    // Insert new user into the database
    $addUserStmt = $conn->prepare("INSERT INTO users (email, password, full_name) VALUES (?, ?, ?)");
    $addUserStmt->bind_param("sss", $newUserEmail, $hashedPassword, $newUserFullName);
    
    if ($addUserStmt->execute()) {
        // Log user addition in the user_activity table
        $activityType = 'add_user';
        $activityTime = date('Y-m-d H:i:s');

        $logStmt = $conn->prepare("INSERT INTO user_activity (user_id, activity_type, activity_time) VALUES (?, ?, ?)");
        $logStmt->bind_param("iss", $_SESSION['user_id'], $activityType, $activityTime);
        $logStmt->execute();
        $logStmt->close();

        $_SESSION['success'] = "User added successfully.";
    } else {
        $_SESSION['error'] = "Error adding user.";
    }

    $addUserStmt->close();
    $conn->close();

    header("Location: admin_dashboard.php"); // Redirect to the admin dashboard or desired page
    exit();
}
?>