<?php

session_start([
    'cookie_lifetime' => 86400,
    'cookie_secure'   => true,
    'cookie_httponly' => true,
    'cookie_samesite' => 'Strict'
]);
include 'config.php';


// Check the request method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed.");
    }
    // Regenerate the CSRF token
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    $conn = new mysqli("localhost", "root", "", "student_college_finder");

    // Check the connection
    if ($conn->connect_error) {
        error_log("Connection failed: " . $conn->connect_error);
        die("An error occurred. Please try again later.");
    }

    // Sanitize and validate inputs
    $fullName = htmlspecialchars(trim($_POST['fullName']));
    $fatherName = htmlspecialchars(trim($_POST['fatherName']));
    $motherName = htmlspecialchars(trim($_POST['motherName']));
    $phoneNumber = filter_var(trim($_POST['phoneNumber']), FILTER_SANITIZE_NUMBER_INT);
    $slcPassoutYear = filter_var(trim($_POST['slcPassoutYear']), FILTER_VALIDATE_INT);
    $plus2PassoutYear = filter_var(trim($_POST['plus2PassoutYear']), FILTER_VALIDATE_INT);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Validate email format
    if (!$email) {
        die("Error: Invalid email format.");
    }

    // Prepare and bind the user insert statement
    $stmt = $conn->prepare("INSERT INTO users (full_name, father_name, mother_name, phone_number, slc_passout_year, plus2_passout_year, email, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $fullName, $fatherName, $motherName, $phoneNumber, $slcPassoutYear, $plus2PassoutYear, $email, $password);
    
    // Execute the statement and check for errors
    if (!$stmt->execute()) {
        error_log("Error: " . $stmt->error);
        die("An error occurred. Please try again later.");
    }

    // Get the inserted user ID
    $userId = $stmt->insert_id;

    // Validate and process file uploads
    $allowedExtensions = ['pdf', 'jpeg', 'jpg', 'png'];
    $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
    $maxFileSize = 5 * 1024 * 1024; // 5MB
    $uploadDir = "uploads/";

    // Ensure the uploads directory exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    foreach (['seeCertificate', 'plus12Certificate'] as $key) {
        if (isset($_FILES[$key]) && $_FILES[$key]['error'] == UPLOAD_ERR_OK) {
            $file = $_FILES[$key];
            $fileName = basename($file['name']);
            $fileTmp = $file['tmp_name'];
            $fileSize = $file['size'];

            // Check file size
            if ($fileSize > $maxFileSize) {
                die("Error: File size exceeds the maximum limit of 5MB.");
            }

            // Check file type using finfo
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $fileType = finfo_file($finfo, $fileTmp);
            finfo_close($finfo);
            if (!in_array($fileType, $allowedTypes)) {
                die("Error: Invalid file type. Only PDF, JPEG, and PNG are allowed.");
            }

            // Validate file extension
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            if (!in_array($fileExtension, $allowedExtensions)) {
                die("Error: Invalid file extension. Allowed extensions are: " . implode(", ", $allowedExtensions));
            }

            // Prevent file overwriting by renaming
            $newFileName = uniqid('cert_', true) . "." . $fileExtension;

            // Move the file to the uploads directory
            if (move_uploaded_file($fileTmp, $uploadDir . $newFileName)) {
                // Insert file data into the certificates table
                $stmt = $conn->prepare("INSERT INTO certificates (user_id, file_name, file_type) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $userId, $newFileName, $key);
                if (!$stmt->execute()) {
                    error_log("Error: " . $stmt->error);
                    die("An error occurred. Please try again later.");
                }
            } else {
                die("Error: There was an issue uploading the file.");
            }
        }
    }

    // Clean up and close the connection
    $stmt->close();
    $conn->close();

    // Redirect to login page
    header("Location: login.php");
    exit();
}
?>