<?php
session_start(); // Start the session

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Optionally, you can set a logout message in a session variable
session_start(); // Start a new session for message
$_SESSION['message'] = "You have been logged out successfully.";

// Redirect after a short delay
header("Refresh: 2; url=index.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Out</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
            font-family: 'Roboto', sans-serif;
        }
        .loading-message {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="loading-message">
        <h2>Logging Out...</h2>
        <p>Please wait while we log you out.</p>
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>