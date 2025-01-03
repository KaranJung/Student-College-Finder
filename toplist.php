<?php
// File path to the JSON file
$collegeRankingsFile = 'C:/xampp/htdocs/Education/json/updated_colleges_nepal.json';

// Fetch and decode the JSON file
$jsonContent = file_get_contents($collegeRankingsFile);
if ($jsonContent === false) {
    die("Unable to read the JSON file.");
}

$collegeRankings = json_decode($jsonContent, true);

// Validate JSON content
if ($collegeRankings === null) {
    die("Invalid JSON: " . json_last_error_msg());
}

// Sort rankings by rating (default to 0 if rating is missing)
usort($collegeRankings, function ($a, $b) {
    return ($b['rating'] ?? 0) <=> ($a['rating'] ?? 0);
});

// Get only the top 20 colleges
$top20Colleges = array_slice($collegeRankings, 0, 20);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student College Finder</title>
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
            font-family: 'Poppins', sans-serif;
            color: var(--text-primary);
            margin: 0;
            background: linear-gradient(to bottom right, #5f9ea0, #f0e68c);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar {
            background: var(--card-bg);
            box-shadow: 0 4px 15px var(--shadow-color);
            padding: 1rem 0;
        }
        .navbar-brand, .nav-link {
            color: var(--text-primary) !important;
            font-weight: 500;
            text-transform: uppercase;
        }
        .hero-section {
            height: 60vh; 
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: #fff;
            position: relative;
        }
        .hero-content {
            position: relative;
            z-index: 2;
        }
        .btn-custom {
            background-color: #ff6f61;
            color: #fff;
            border-radius: 50px;
            transition: background-color 0.3s, transform 0.3s;
            padding: 10px 20px;
        }
        .btn-custom:hover {
            background-color: #e64a47;
            transform: translateY(-2px);
        }
        footer {
            background-color: #212529;
            color: #fff;
            text-align: center;
            padding: 20px 0;
            margin-top: auto;
        }
        .feature-section {
            padding: 60px 0;
            background-color: rgba(255, 255, 255, 0.8);
        }
        .feature-icon {
            font-size: 40px;
            color: #ff6f61;
            margin-bottom: 15px;
        }
        .table-section {
            padding: 60px 15px;
            background-color: rgba(255, 255, 255, 0.9);
        }
        .table-container {
            box-shadow: 0 4px 15px var(--shadow-color);
            border-radius: 8px;
            overflow: hidden;
        }
        .table {
            margin: 0;
            border-collapse: collapse;
            width: 100%;
        }
        .table th, .table td {
            text-align: left;
            padding: 12px;
            vertical-align: middle;
        }
        .table th {
            background-color: var(--primary-color);
            color: #fff;
            position: sticky;
            top: 0;
            z-index: 1;
        }
        .table tbody tr {
            transition: background-color 0.3s;
        }
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        .rating-stars {
            color: #f1c40f;
        }
        .badge {
            background-color: var(--primary-color);
            color: #fff;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            margin: 2px;
        }
        .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-graduation-cap"></i> Student College Finder
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <!-- Additional nav items can go here -->
            </ul>
            <div class="d-flex">
                <a class="nav-link me-3" href="login.php">Login</a>
                <a class="nav-link me-3" href="register.php">Register</a>
                <a class="nav-link me-3" href="aboutus.php">About Us</a>
            </div>
        </div>
    </div>
</nav>

<!-- College Rankings Section -->
<section class="table-section">
    <div class="container table-container">
        <h2 class="text-center mb-4">Top 20 Colleges in Nepal 2024</h2>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>College Name</th>
                        <th>Location</th>
                        <th>Rating</th>
                        <th>Courses Offered</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($top20Colleges as $index => $college): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($college['College']) ?></td>
                            <td><?= htmlspecialchars($college['Location'] ?? 'Unknown Location') ?></td>
                            <td>
                                <span class="rating-stars">
                                    <?php
                                    $rating = $college['rating'] ?? 0;
                                    for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="<?= $i <= $rating ? 'fas' : 'far' ?> fa-star"></i>
                                    <?php endfor; ?>
                                </span>
                                <small><?= number_format($rating, 1) ?> / 5.0</small>
                            </td>
                            <td>
                                <?php
                                $courses = explode("\n", $college['Course Offered'] ?? 'No courses available');
                                $displayCourses = array_slice($courses, 0, 3);
                                foreach ($displayCourses as $course): ?>
                                    <span class="badge bg-success"><?= htmlspecialchars(trim($course)) ?></span>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    <p>&copy; 2024 Student College Finder. All rights reserved.</p>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>