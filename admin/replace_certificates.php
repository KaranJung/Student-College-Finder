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

// Get user ID from the URL
$userId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $slcCertificate = $_FILES['slc_certificate'];
    $plus2Certificate = $_FILES['plus2_certificate'];

    // Upload SLC certificate
    if ($slcCertificate['error'] === UPLOAD_ERR_OK) {
        $slcFileName = 'cert_' . uniqid() . '_' . basename($slcCertificate['name']);
        move_uploaded_file($slcCertificate['tmp_name'], 'uploads/' . $slcFileName);
        $stmt = $conn->prepare("UPDATE certificates SET file_name = ? WHERE user_id = ? AND file_type = 'seeCertificate'");
        $stmt->bind_param("si", $slcFileName, $userId);
        $stmt->execute();
    }

    // Upload +2 certificate
    if ($plus2Certificate['error'] === UPLOAD_ERR_OK) {
        $plus2FileName = 'cert_' . uniqid() . '_' . basename($plus2Certificate['name']);
        move_uploaded_file($plus2Certificate['tmp_name'], 'uploads/' . $plus2FileName);
        $stmt = $conn->prepare("UPDATE certificates SET file_name = ? WHERE user_id = ? AND file_type = 'plus2Certificate'");
        $stmt->bind_param("si", $plus2FileName, $userId);
        $stmt->execute();
    }

    $_SESSION['toast_message'] = "Certificates replaced successfully.";
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Replace Certificates</title>
    <!-- MDBootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Replace Certificates</h1>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="slc_certificate" class="form-label">SLC Certificate</label>
                <input type="file" class="form-control" id="slc_certificate" name="slc_certificate" required>
            </div>
            <div class="mb-3">
                <label for="plus2_certificate" class="form-label">+2 Certificate</label>
                <input type="file" class="form-control" id="plus2_certificate" name="plus2_certificate" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>

    <!-- MDBootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>
</body>
</html>