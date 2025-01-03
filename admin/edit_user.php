<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Database connection
$conn = new mysqli("localhost", "root", "", "student_college_finder");
if ($conn->connect_error) {
    die("Connection failed: " . htmlspecialchars($conn->connect_error));
}

// Initialize variables
$message = '';
$userId = intval($_GET['id'] ?? 0);

// Fetch user data
if ($userId > 0) {
    $userResult = $conn->query("SELECT * FROM users WHERE id = $userId");
    
    // Check if query executed successfully
    if (!$userResult) {
        die("Query failed: " . htmlspecialchars($conn->error));
    }

    $user = $userResult->fetch_assoc();

    // Check if the user exists
    if (!$user) {
        die("User not found.");
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['full_name'];
    $fatherName = $_POST['father_name'];
    $motherName = $_POST['mother_name'];
    $phoneNumber = $_POST['phone_number'];
    $slcPassoutYear = $_POST['slc_passout_year'];
    $plus2PassoutYear = $_POST['plus2_passout_year'];
    $email = $_POST['email'];

    // Update user in the database
    $stmt = $conn->prepare("UPDATE users SET full_name = ?, father_name = ?, mother_name = ?, phone_number = ?, slc_passout_year = ?, plus2_passout_year = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssssiiii", $fullName, $fatherName, $motherName, $phoneNumber, $slcPassoutYear, $plus2PassoutYear, $email, $userId);

    if ($stmt->execute()) {
        $message = "User updated successfully.";
    } else {
        $message = "Error updating user: " . htmlspecialchars($stmt->error);
    }

    $stmt->close();
    $userResult = $conn->query("SELECT * FROM users WHERE id = $userId"); // Refresh user data
    $user = $userResult->fetch_assoc(); // Get updated user data
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: #e0e5ec;
        }
        .navbar {
            background: #f9f9f9;
            border-radius: 0 0 20px 20px;
            box-shadow: 10px 10px 30px #d1d9e4,
                        -10px -10px 30px #ffffff;
        }
        .container {
            margin-top: 30px;
        }
        .neumorphic-form {
            border-radius: 20px;
            padding: 20px;
            box-shadow: 10px 10px 30px #d1d9e4,
                        -10px -10px 30px #ffffff;
            background: #f9f9f9;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Admin Panel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Manage Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reports.php">Reports</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <h1 class="text-center">Edit User</h1>
    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>
    
    <div class="neumorphic-form">
        <form method="POST" action="">
            <div class="mb-3">
                <label for="full_name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="full_name" name="full_name" value="<?= htmlspecialchars($user['full_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="father_name" class="form-label">Father's Name</label>
                <input type="text" class="form-control" id="father_name" name="father_name" value="<?= htmlspecialchars($user['father_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="mother_name" class="form-label">Mother's Name</label>
                <input type="text" class="form-control" id="mother_name" name="mother_name" value="<?= htmlspecialchars($user['mother_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="phone_number" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?= htmlspecialchars($user['phone_number']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="slc_passout_year" class="form-label">SLC Passout Year</label>
                <input type="number" class="form-control" id="slc_passout_year" name="slc_passout_year" value="<?= htmlspecialchars($user['slc_passout_year']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="plus2_passout_year" class="form-label">Plus 2 Passout Year</label>
                <input type="number" class="form-control" id="plus2_passout_year" name="plus2_passout_year" value="<?= htmlspecialchars($user['plus2_passout_year']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update User</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>