<?php
// Start session
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit;
}

$symbolNumber = "";
$result = "";
$universityInfo = "";
$examDate = "";
$passedStudentsRollNo = [];

// Path to your JSON file
$jsonFilePath = 'C:\xampp\htdocs\Education\json\bca_2019.json';

// Check if the JSON file exists and is readable
if (file_exists($jsonFilePath)) {
    // Load and decode the JSON data
    $jsonData = file_get_contents($jsonFilePath);
    $data = json_decode($jsonData, true);

    // Extract details and roll numbers
    foreach ($data as $entry) {
        foreach ($entry as $key => $value) {
            // Capture university information and exam date
            if (strpos($key, 'Tribhuvan University') !== false) {
                $universityInfo = $key;
                $examDate = "Exam held in September, 2024"; // Hardcoded based on provided JSON
            }
            // Check if the value is a roll number (assuming 8 digits)
            if (preg_match('/^\d{8}$/', trim($value))) {
                $passedStudentsRollNo[] = trim($value); // Add valid roll numbers to the array
            }
        }
    }
} else {
    die("Error: bca.json file not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the symbol number from the form input
    $symbolNumber = trim($_POST['symbolNumber']);

    // Check if the symbol number exists in the list of passed students
    if (!empty($symbolNumber)) {
        if (in_array($symbolNumber, $passedStudentsRollNo)) {
            $result = "Pass";
        } else {
            $result = "No record found"; // Update result for no matching roll number
        }
    } else {
        $result = "No roll number entered"; // Handle empty input
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Myresult - Student College Finder</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2ecc71;
            --background: #f0f2f5;
            --card-bg: #ffffff;
            --text-primary: #2c3e50;
            --shadow-color: rgba(0, 0, 0, 0.1);
        }

        body {
            background-color: var(--background);
            font-family: 'Poppins', sans-serif;
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        .navbar {
            background: var(--card-bg);
            box-shadow: 0 4px 15px var(--shadow-color);
            padding: 1rem 0;
        }

        .navbar-brand, .nav-link {
            color: var(--text-primary) !important;
            font-weight: 500;
        }

        .search-container {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .search-input {
            width: 300px; /* Adjust width as needed */
            border-radius: 25px;
            border: 1px solid var(--primary-color);
            padding: 10px;
            transition: box-shadow 0.3s ease;
        }

        .search-input:focus {
            box-shadow: 0 0 5px var(--primary-color);
            outline: none;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-radius: 25px;
            border: none;
            padding: 10px 15px;
            margin-left: 10px;
        }

        .btn-primary:hover {
            background-color: #218838;
        }

        .result-message {
            font-size: 1.2rem;
            font-weight: bold;
            margin-top: 20px;
            animation: fadeIn 1s ease-out;
        }

        .result-message.pass {
            color: #28a745; /* Green for pass */
        }
        
        .result-message.fail {
            color: #dc3545; /* Red for fail */
        }

        .alert-custom {
            max-width: 400px; /* Set max width to make it smaller */
            margin: 20px auto; /* Center the alert box */
            padding: 15px; /* Add padding */
            border-radius: 10px; /* Rounded corners */
        }

        .find-college-btn {
            display: block;
            background-color: #ffeb3b;
            color: #000;
            padding: 10px 20px;
            font-size: 1.2rem;
            border-radius: 5px;
            text-decoration: none;
            transition: transform 0.3s ease, background-color 0.3s ease;
            margin-top: 20px;
            text-align: center; /* Center the button text */
        }

        .find-college-btn:hover {
            background-color: #ffc107;
            transform: scale(1.1);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-graduation-cap"></i> Student College Finder
        </a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Colleges.php">Find Colleges</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="showmap.php">View Map</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Settings
                    </a>
                    <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="change_profile.php">Edit Profile</a></li>
                        <li><a class="dropdown-item" href="change_password.php">Change Password</a></li>
                    </ul>
                   
                </li>
            </ul>
            <div class="d-flex">
                <a href="logout.php" class="btn btn-light">Logout</a>
            </div>
        </div>
    </div>
</nav>

<div class="container">
    <h2 class="text-center mt-4">Check Your Result</h2> <!-- Added mt-4 class for margin-top -->
    <form method="POST" action="" class="text-center">
        <div class="search-container">
            <input type="text" class="form-control search-input" id="symbolNumber" name="symbolNumber" required placeholder="Enter Symbol Number" value="<?php echo htmlspecialchars($symbolNumber); ?>">
            <button type="submit" class="btn btn-primary">Check Result</button>
        </div>
    </form>
    
    <!-- Display result and university info if available -->
    <?php if ($result !== ""): ?>
        <div class="alert alert-<?php echo ($result === "Pass") ? 'success' : 'danger'; ?> alert-custom">
            <div class="result-message <?php echo ($result === "Pass") ? 'pass' : 'fail'; ?>">
                <?php if ($result === "No record found" || $result === "No roll number entered"): ?>
                    <strong><?php echo $result; ?></strong>
                <?php else: ?>
                    <i class="bi bi-check-circle"></i> You have <strong><?php echo $result; ?></strong> the exam!
                <?php endif; ?>
            </div>
            <?php if ($result === "Pass"): ?>
                <div class="mt-3">
                    <strong><?php echo htmlspecialchars($universityInfo); ?></strong><br>
                    <em><?php echo htmlspecialchars($examDate); ?></em>
                    <a href="Colleges.php" class="find-college-btn">Search Colleges</a> <!-- Button to search colleges -->
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Optionally include Bootstrap JS for additional features -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>