<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Database connection
$conn = new mysqli("localhost", "root", "", "student_college_finder");
if ($conn->connect_error) {
    die("Connection failed: " . htmlspecialchars($conn->connect_error));
}

$userId = $_SESSION['user_id'];
$message = '';

// Fetch current user data
$stmt = $conn->prepare("SELECT full_name, father_name, mother_name, phone_number, slc_passout_year, plus2_passout_year, email FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($full_name, $father_name, $mother_name, $phone_number, $slc_passout_year, $plus2_passout_year, $email);
$stmt->fetch();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $father_name = trim($_POST['father_name']);
    $mother_name = trim($_POST['mother_name']);
    $phone_number = trim($_POST['phone_number']);
    $slc_passout_year = trim($_POST['slc_passout_year']);
    $plus2_passout_year = trim($_POST['plus2_passout_year']);
    $email = trim($_POST['email']);

    // Input validation
    if (empty($full_name) || empty($father_name) || empty($mother_name) || empty($phone_number) || 
        empty($slc_passout_year) || empty($plus2_passout_year) || empty($email)) {
        $message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } elseif (!preg_match('/^[0-9]{10}$/', $phone_number)) {
        $message = "Phone number must be 10 digits.";
    } else {
        // Update user data
        $updateStmt = $conn->prepare("UPDATE users SET full_name = ?, father_name = ?, mother_name = ?, phone_number = ?, slc_passout_year = ?, plus2_passout_year = ?, email = ? WHERE id = ?");
        $updateStmt->bind_param("sssssisi", $full_name, $father_name, $mother_name, $phone_number, $slc_passout_year, $plus2_passout_year, $email, $userId);
        
        if ($updateStmt->execute()) {
            $message = "Profile updated successfully!";
        } else {
            $message = "Error updating profile. Please try again.";
        }
        $updateStmt->close();
    }
}

// Fetch certificates for the user
$certificatesStmt = $conn->prepare("SELECT id, file_name FROM certificates WHERE user_id = ?");
$certificatesStmt->bind_param("i", $userId);
$certificatesStmt->execute();
$certificatesResult = $certificatesStmt->get_result();
$certificates = $certificatesResult->fetch_all(MYSQLI_ASSOC);
$certificatesStmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Roboto', sans-serif;
        }
        .container {
            margin-top: 50px;
            max-width: 600px;
        }
        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .btn-primary {
            background-color: #28a745;
            border: none;
        }
        .btn-primary:hover {
            background-color: #218838;
        }

        .input-group input:focus, .form-control:focus {
            box-shadow: 0 0 8px rgba(40, 167, 69, 0.5);
            border-color: #28a745;
        }

        .alert {
            animation: fadeIn 2s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .certificate-item {
            transition: all 0.3s ease;
        }

        .certificate-item:hover {
            transform: scale(1.05);
            background-color: #f1f1f1;
        }

    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-header text-white bg-success">
            <h4>Edit Profile</h4>
        </div>
        <div class="card-body">
            <?php if ($message): ?>
                <div class="alert <?= strpos($message, 'successfully') !== false ? 'alert-success' : 'alert-danger'; ?>">
                    <?= htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="full_name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" value="<?= htmlspecialchars($full_name); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="father_name" class="form-label">Father's Name</label>
                    <input type="text" class="form-control" id="father_name" name="father_name" value="<?= htmlspecialchars($father_name); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="mother_name" class="form-label">Mother's Name</label>
                    <input type="text" class="form-control" id="mother_name" name="mother_name" value="<?= htmlspecialchars($mother_name); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?= htmlspecialchars($phone_number); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="slc_passout_year" class="form-label">SLC Passout Year</label>
                    <input type="number" class="form-control" id="slc_passout_year" name="slc_passout_year" value="<?= htmlspecialchars($slc_passout_year); ?>" required min="1900" max="<?= date("Y"); ?>">
                </div>
                <div class="mb-3">
                    <label for="plus2_passout_year" class="form-label">Plus 2 Passout Year</label>
                    <input type="number" class="form-control" id="plus2_passout_year" name="plus2_passout_year" value="<?= htmlspecialchars($plus2_passout_year); ?>" required min="1900" max="<?= date("Y"); ?>">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Profile</button>
                <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
            </form>

            <hr>
            <h5>Certificates</h5>
            <ul class="list-group">
                <?php foreach ($certificates as $certificate): ?>
                    <li class="list-group-item certificate-item">
                        <?= htmlspecialchars($certificate['file_name']); ?>
                        <a href="download_certificate.php?id=<?= htmlspecialchars($certificate['id']); ?>" class="btn btn-link">Download</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
