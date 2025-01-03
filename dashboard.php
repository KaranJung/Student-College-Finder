<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "student_college_finder");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId = $_SESSION['user_id'];

// Fetch user profile data
$stmt = $conn->prepare("SELECT full_name, father_name, mother_name, phone_number, slc_passout_year, plus2_passout_year, email FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($fullName, $fatherName, $motherName, $phoneNumber, $slcPassoutYear, $plus2PassoutYear, $email);
$stmt->fetch();
$stmt->close();

// Fetch certificates
$certificates = [];
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$query = "SELECT file_name, file_type FROM certificates WHERE user_id = ?" . ($filter ? " AND file_type = ?" : "");
$certStmt = $conn->prepare($query);

if ($filter) {
    $certStmt->bind_param("is", $userId, $filter);
} else {
    $certStmt->bind_param("i", $userId);
}

$certStmt->execute();
$certResult = $certStmt->get_result();

while ($row = $certResult->fetch_assoc()) {
    $certificates[] = $row;
}

$certStmt->close();

// Load JSON file
$jsonData = file_get_contents('C:\xampp\htdocs\Education\json\Colleges.json');
$collegesArray = json_decode($jsonData, true);

// Search functionality
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$filteredColleges = [];

if ($searchQuery) {
    $searchQuery = strtolower($searchQuery);
    $filteredColleges = array_filter($collegesArray, function($college) use ($searchQuery) {
        return stripos(strtolower($college['College']), $searchQuery) !== false ||
               stripos(strtolower($college['Course Offered']), $searchQuery) !== false ||
               stripos(strtolower($college['Location']), $searchQuery) !== false ||
               stripos(strtolower($college['University']), $searchQuery) !== false;
    });
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Student College Finder</title>
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

        .college-card {
            background: var(--card-bg);
            border-radius: 15px;
            box-shadow: 8px 8px 15px var(--shadow-color);
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .college-card:hover {
            transform: translateY(-5px);
            box-shadow: 12px 12px 20px var(--shadow-color);
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-title {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .card-text {
            margin-bottom: 0.5rem;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px var(--shadow-color);
            text-align: center;
            margin-bottom: 20px;
        }

        .stat-card h5 {
            margin-bottom: 0.5rem;
        }

        .certificate {
            margin-bottom: 20px;
            padding: 1rem;
            border: 1px solid var(--primary-color);
            border-radius: 10px;
            background: #fff;
        }

        .certificate h6 {
            margin-bottom: 10px;
        }

        .modal-body {
            text-align: center;
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
                    <a class="nav-link" href="Colleges.php">Find Colleges</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="myresult.php">Check Results</a>
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

<div class="container mt-5">
    <div class="row">
        <!-- Profile Section -->
        <div class="col-md-6">
            <div class="profile-header">
                <h4>Student Profile</h4>
            </div>
            <div class="profile-details">
                <ul class="list-group">
                    <li class="list-group-item"><strong>Full Name:</strong> <?php echo htmlspecialchars($fullName); ?></li>
                    <li class="list-group-item"><strong>Father's Name:</strong> <?php echo htmlspecialchars($fatherName); ?></li>
                    <li class="list-group-item"><strong>Mother's Name:</strong> <?php echo htmlspecialchars($motherName); ?></li>
                    <li class="list-group-item"><strong>Phone Number:</strong> <?php echo htmlspecialchars($phoneNumber); ?></li>
                    <li class="list-group-item"><strong>SLC Passout Year:</strong> <?php echo htmlspecialchars($slcPassoutYear); ?></li>
                    <li class="list-group-item"><strong>+2 Passout Year:</strong> <?php echo htmlspecialchars($plus2PassoutYear); ?></li>
                    <li class="list-group-item"><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></li>
                </ul>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="col-md-6">
            <div class="stats-header">
                <h4>Dashboard Statistics</h4>
            </div>
            <div class="stats-details">
                <div class="row">
                    <div class="col-md-6">
                        <div class="stat-card">
                            <h5>Total Certificates</h5>
                            <p><?php echo count($certificates); ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stat-card">
                            <h5>Total Colleges Found</h5>
                            <p><?php echo count($filteredColleges); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Certificate Filter -->
    <form method="GET" class="my-3">
        <label for="filter" class="form-label">Filter Certificates:</label>
        <select name="filter" id="filter" class="form-select">
            <option value="">All</option>
            <option value="seeCertificate" <?php echo $filter == 'seeCertificate' ? 'selected' : ''; ?>>SEE Certificate</option>
            <option value="plus12Certificate" <?php echo $filter == 'plus12Certificate' ? 'selected' : ''; ?>>+2 Certificate</option>
        </select>
        <button type="submit" class="btn btn-dark mt-2">Apply Filter</button>
    </form>

    <div class="row">
        <?php foreach ($certificates as $certificate): ?>
            <div class="col-md-6">
                <div class="certificate">
                    <h6><?php echo strtoupper($certificate['file_type']); ?></h6>
                    <a href="uploads/<?php echo htmlspecialchars($certificate['file_name']); ?>" class="btn btn-primary" download>Download</a>
                    <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#viewModal" data-file="uploads/<?php echo htmlspecialchars($certificate['file_name']); ?>">View</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <hr>

    <!-- Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel">View Certificate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe id="certificateFrame" src="" width="100%" height="400px" style="border: none;"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Set the iframe source when the "View" button is clicked
        const viewButtons = document.querySelectorAll('[data-bs-target="#viewModal"]');
        viewButtons.forEach(button => {
            button.addEventListener('click', function() {
                const file = this.getAttribute('data-file');
                document.getElementById('certificateFrame').src = file;
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>