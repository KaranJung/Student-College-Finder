<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Load colleges data from JSON file
$colleges = [];
$jsonFilePath = 'C:\xampp\htdocs\Education\json\updated_colleges_nepal.json';
if (file_exists($jsonFilePath)) {
    $jsonData = file_get_contents($jsonFilePath);
    $colleges = json_decode($jsonData, true);
    if ($colleges === null) {
        die("Error: Invalid JSON data in updated_colleges_nepal.json.");
    }
} else {
    die("Error: updated_colleges_nepal.json file not found.");
}

// Get college name and location from URL parameters
$collegeName = isset($_GET['college']) ? $_GET['college'] : '';
$latitude = isset($_GET['latitude']) ? (float)$_GET['latitude'] : 27.7172; // Default to Kathmandu
$longitude = isset($_GET['longitude']) ? (float)$_GET['longitude'] : 85.324; // Default to Kathmandu
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map - Student College Finder</title>
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

        #map {
            height: 70vh;
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 4px 15px var(--shadow-color);
            margin-top: 20px;
        }
    </style>
</head>
<body onload="initMap()">

<script src="https://maps.googleapis.com/maps/api/js?key=(ADD YOUR GOOGLE MAP API)&libraries=places"></script>
<script>
    function initMap() {
        const location = { lat: <?php echo $latitude; ?>, lng: <?php echo $longitude; ?> };
        const map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15,
            center: location
        });

        const marker = new google.maps.Marker({
            position: location,
            map: map,
            title: "<?php echo htmlspecialchars($collegeName); ?>"
        });

        const infowindow = new google.maps.InfoWindow({
            content: "<strong><?php echo htmlspecialchars($collegeName); ?></strong>"
        });

        marker.addListener('click', function() {
            infowindow.open(map, marker);
        });
    }
</script>

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
                    <a class="nav-link" href="Colleges.php">Find College</a>
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

<div class="container mt-3">
    <div id="map"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>