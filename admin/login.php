<?php
session_start();
$message = '';

// Security: Limit login attempts
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}
if ($_SESSION['login_attempts'] >= 5) {
    die("Too many failed login attempts. Please try again later.");
}

// Check if the admin is already logged in
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}

// Database connection
$conn = new mysqli("localhost", "root", "", "student_college_finder");
if ($conn->connect_error) {
    die("Connection failed: " . htmlspecialchars($conn->connect_error));
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Sanitize input
    $username = htmlspecialchars($username);

    // Validate credentials
    $stmt = $conn->prepare("SELECT password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if the username exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashedPassword)) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['login_attempts'] = 0; // Reset attempts on successful login
            header("Location: index.php");
            exit;
        } else {
            $_SESSION['login_attempts']++;
            $message = "Invalid username or password.";
        }
    } else {
        $_SESSION['login_attempts']++;
        $message = "Invalid username or password.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #4CAF50, #2196F3);
            font-family: 'Roboto', sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
            width: 400px;
            text-align: center;
        }
        .login-container h1 {
            margin-bottom: 1.5rem;
            color: #333;
        }
        .btn-custom {
            background: #2196F3;
            color: #fff;
            border: none;
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            transition: 0.3s ease;
        }
        .btn-custom:hover {
            background: #1769aa;
        }
        .form-control {
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .alert {
            text-align: left;
            margin-bottom: 1rem;
        }
        .small-text {
            margin-top: 1rem;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
<div class="login-container">
    <h1>Admin Login</h1>
    <?php if ($message): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="form-group">
            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
        </div>
        <div class="form-group">
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-custom">Login</button>
    </form>
    <div class="small-text">
        © 2024 Student College Finder. All rights reserved.
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>