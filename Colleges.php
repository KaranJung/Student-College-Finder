<?php
// Start session
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Connect to the database
try {
    $conn = new mysqli("localhost", "root", "", "student_college_finder");
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die($e->getMessage());
}

$userId = $_SESSION['user_id'];

// Load JSON file with college data
$jsonData = @file_get_contents('C:\xampp\htdocs\Education\json\updated_colleges_nepal.json');
if ($jsonData === false) {
    die("Error: Failed to read the colleges JSON file.");
}

$collegesArray = json_decode($jsonData, true);
if ($collegesArray === null) {
    die("Error: Invalid JSON format in the colleges data.");
}

// Filter inputs from the GET request and sanitize
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$locationFilter = isset($_GET['location']) ? trim($_GET['location']) : '';
$courseFilter = isset($_GET['course']) ? trim($_GET['course']) : '';

// Filter colleges based on the inputs
$filteredColleges = array_filter($collegesArray, function($college) use ($searchQuery, $locationFilter, $courseFilter) {
    $matchesSearch = $searchQuery ? stripos($college['College'], $searchQuery) !== false : true;
    $matchesLocation = $locationFilter ? stripos($college['Location'], $locationFilter) !== false : true;
    $matchesCourse = $courseFilter ? stripos($college['Course Offered'], $courseFilter) !== false : true;

    return $matchesSearch && $matchesLocation && $matchesCourse;
});

// Get the number of filtered colleges
$numberOfColleges = count($filteredColleges);

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find College - Student College Finder</title>
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

        .college-card {
            background: var(--card-bg);
            border-radius: 15px;
            box-shadow: 8px 8px 15px var(--shadow-color);
            transition: all 0.3s ease;
            height: 100%;
            min-height: 320px;
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

        .search-header {
            margin: 2rem 0;
        }

        /* New Modal Specific Styles */
        :root {
            --modal-bg-light: #f5f7fa;
            --modal-bg-dark: #2c3e50;
            --modal-text-light: #2c3e50;
            --modal-text-dark: #ecf0f1;
        }

        .modal-content {
            border-radius: 20px;
            overflow: hidden;
            background: linear-gradient(135deg, var(--modal-bg-light) 0%, #e9ecef 100%);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transition: all 0.3s ease;
        }

        .modal-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1.5rem;
            position: relative;
        }

        .modal-header .btn-close {
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            opacity: 1;
        }

        .modal-body {
            padding: 2rem;
        }

        .college-detail {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            background-color: rgba(255,255,255,0.7);
            padding: 0.75rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .college-detail:hover {
            background-color: rgba(255,255,255,0.9);
            transform: translateX(10px);
        }

        .college-detail i {
            margin-right: 1rem;
            color: var(--primary-color);
            font-size: 1.5rem;
        }

        .modal-footer {
            background-color: #f1f3f5;
            border-top: 1px solid #e9ecef;
        }

        #modalDescription {
            font-style: italic;
            color: #6c757d;
            line-height: 1.6;
        }

        .quick-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 1rem;
        }

        .quick-action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45%;
            padding: 0.75rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .quick-action-btn i {
            margin-right: 0.5rem;
        }

        .contact-action {
            background-color: #3498db;
            color: white;
        }

        .map-action {
            background-color: #2ecc71;
            color: white;
        }

        .quick-action-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        /* Dark mode support for modal */
        @media (prefers-color-scheme: dark) {
            .modal-content {
                background: linear-gradient(135deg, var(--modal-bg-dark) 0%, #34495e 100%);
                color: var(--modal-text-dark);
            }

            .college-detail {
                background-color: rgba(44, 62, 80, 0.7);
            }

            #modalDescription {
                color: #bdc3c7;
            }
        }
    </style>
</head>
<body>
    <!-- Previous HTML content -->
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
    <div class="search-header">
        <h4>Find Colleges</h4>
        <form method="GET">
            <div class="row">
                <div class="col-md-4 col-12 mb-3">
                    <input type="text" name="search" class="form-control" placeholder="Search by college name..." value="<?php echo htmlspecialchars($searchQuery); ?>">
                </div>
                <div class="col-md-4 col-12 mb-3">
                    <input type="text" name="location" class="form-control" placeholder="Search by location..." value="<?php echo htmlspecialchars($locationFilter); ?>">
                </div>
                <div class="col-md-4 col-12 mb-3">
                    <input type="text" name="course" class="form-control" placeholder="Search by course offered..." value="<?php echo htmlspecialchars($courseFilter); ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-dark mt-3">Search</button>
        </form>
    </div>

    <?php if ($searchQuery || $locationFilter || $courseFilter): ?>
        <?php if ($filteredColleges): ?>
            <h5 class="mt-4">Found <?php echo $numberOfColleges; ?> colleges based on your search criteria:</h5>
            <div class="row">
                <?php foreach ($filteredColleges as $college): ?>
                    <div class="col-md-4 col-12 mb-4">
                        <div class="card college-card" data-college='<?= json_encode($college) ?>' data-bs-toggle="modal" data-bs-target="#collegeModal">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($college['College']); ?></h5>
                                <p class="card-text"><strong>Location:</strong> <?php echo htmlspecialchars($college['Location']); ?></p>
                                <p class="card-text"><strong>Course Offered:</strong> <?php echo htmlspecialchars($college['Course Offered']); ?></p>
                                <p class="card-text"><strong>University:</strong> <?php echo htmlspecialchars($college['University']); ?></p>
                                <p class="card-text"><strong>Phone:</strong> <?php echo htmlspecialchars($college['Phone Number']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No results found for your search criteria.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>
    <!-- Enhanced Modal Structure -->
    <div class="modal fade" id="collegeModal" tabindex="-1" aria-labelledby="collegeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h4 class="modal-title" id="modalCollegeName"></h4>
                        <small id="modalUniversity" class="text-white opacity-75"></small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="college-detail">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <strong>Location:</strong>
                            <span id="modalLocation"></span>
                        </div>
                    </div>

                    <div class="college-detail">
                        <i class="fas fa-graduation-cap"></i>
                        <div>
                            <strong>Courses Offered:</strong>
                            <span id="modalCourseOffered"></span>
                        </div>
                    </div>

                    <div class="college-detail">
                        <i class="fas fa-building"></i>
                        <div>
                            <strong>Ownership Type:</strong>
                            <span id="modalOwnershipType"></span>
                        </div>
                    </div>

                    <div class="college-detail">
                        <i class="fas fa-phone"></i>
                        <div>
                            <strong>Contact:</strong>
                            <span id="modalPhoneNumber"></span>
                        </div>
                    </div>

                    <div class="college-detail">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <strong>Email:</strong>
                            <span id="modalEmail"></span>
                        </div>
                    </div>

                    <div class="college-detail">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            <strong>Description:</strong>
                            <p id="modalDescription" class="mb-0"></p>
                        </div>
                    </div>

                    <div class="quick-actions">
                        <a href="#" id="contactCollegeBtn" class="quick-action-btn contact-action">
                            <i class="fas fa-comment-dots"></i> Contact
                        </a>
                        <a href="#" id="viewMapLink" class="quick-action-btn map-action">
                            <i class="fas fa-map"></i> View Map
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Populate modal with college details
        const collegeCards = document.querySelectorAll('.college-card');
        collegeCards.forEach(card => {
            card.addEventListener('click', function() {
                const college = JSON.parse(this.getAttribute('data-college'));
                
                // Populate modal fields
                document.getElementById('modalCollegeName').innerText = college.College;
                document.getElementById('modalUniversity').innerText = college.University || 'Unknown University';
                document.getElementById('modalLocation').innerText = college.Location;
                document.getElementById('modalCourseOffered').innerText = college['Course Offered'];
                document.getElementById('modalOwnershipType').innerText = college['Ownership Type'] || 'N/A';
                document.getElementById('modalPhoneNumber').innerText = college['Phone Number'] || 'Not Available';
                document.getElementById('modalEmail').innerText = college.Email || 'Not Available';
                document.getElementById('modalDescription').innerText = college.Description || 'No description available.';
                
                
                // Set map and contact links
                const mapLink = `map.php?college=${encodeURIComponent(college.College)}&latitude=${college.latitude}&longitude=${college.longitude}`;
                document.getElementById('viewMapLink').href = mapLink;
                
                const contactLink = `contact.php?college=${encodeURIComponent(college.College)}`;
                document.getElementById('contactCollegeBtn').href = contactLink;
            });
        });
    </script>
</body>
</html>