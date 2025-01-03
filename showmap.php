<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$collegesJson = file_get_contents('updated_colleges_nepal.json');
$colleges = json_decode($collegesJson, true);

$apiKey = '(ADD YOUR GOOGLE MAP API)';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Map - Student College Finder</title>
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

        #map-container {
            height: calc(100vh - 76px);
            display: flex;
        }

        #map {
            flex: 1;
            height: 100%;
        }

        #college-list {
            width: 400px;
            height: 100%;
            overflow-y: auto;
            padding: 1rem;
            background-color: var(--card-bg);
            box-shadow: -4px 0 15px var(--shadow-color);
        }

        #rating-filter {
            margin-bottom: 1rem;
        }

        .college-item {
            margin-bottom: 1rem;
            padding: 1rem;
            background-color: var(--background);
            border-radius: 8px;
            box-shadow: 0 2px 5px var(--shadow-color);
        }

        .college-item h5 {
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }

        @media (max-width: 768px) {
            #map-container {
                flex-direction: column;
            }

            #map {
                height: 50%;
            }

            #college-list {
                width: 100%;
                height: 50%;
            }
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

<div id="map-container">
    <div id="map"></div>
    <div id="college-list">
        <div id="rating-filter" class="mb-3">
            <label for="min-rating" class="form-label">Minimum Rating:</label>
            <input type="number" id="min-rating" class="form-control" min="0" max="5" step="0.1" value="0">
        </div>
        <div id="college-items"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let map;
    let markers = [];
    const colleges = <?php echo $collegesJson; ?>;

    // Function to initialize the map
    function initMap() {
        const nepal = { lat: 28.3949, lng: 84.1240 };
        map = new google.maps.Map(document.getElementById("map"), {
            zoom: 7,
            center: nepal,
        });

        updateColleges();

        document.getElementById('min-rating').addEventListener('input', updateColleges);
    }

    // Function to update colleges based on rating
    function updateColleges() {
        const minRating = parseFloat(document.getElementById('min-rating').value);
        const filteredColleges = colleges.filter(college => college.rating >= minRating);

        // Clear existing markers
        markers.forEach(marker => marker.setMap(null));
        markers = [];

        // Clear existing college items
        document.getElementById('college-items').innerHTML = '';

        filteredColleges.forEach(college => {
            if (college.latitude && college.longitude) {
                const marker = new google.maps.Marker({
                    position: { lat: college.latitude, lng: college.longitude },
                    map: map,
                    title: college.College
                });

                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div>
                            <h3>${college.College}</h3>
                            <p><strong>Location:</strong> ${college.Location}</p>
                            <p><strong>University:</strong> ${college.University}</p>
                            <p><strong>Courses Offered:</strong> ${college["Course Offered"]}</p>
                            <p><strong>Ownership:</strong> ${college["Ownership Type"]}</p>
                            <p><strong>Phone:</strong> ${college["Phone Number"]}</p>
                            <p><strong>Email:</strong> ${college.Email}</p>
                            <p><strong>Rating:</strong> ${college.rating}/5</p>
                        </div>
                    `
                });

                marker.addListener("click", () => {
                    infoWindow.open(map, marker);
                });

                markers.push(marker);

                // Add college to the list
                const collegeItem = document.createElement('div');
                collegeItem.className = 'college-item';
                collegeItem.innerHTML = `
                    <h5>${college.College}</h5>
                    <p><strong>Location:</strong> ${college.Location}</p>
                    <p><strong>Ownership:</strong> ${college["Ownership Type"]}</p>
                    <p><strong>Phone:</strong> ${college["Phone Number"]}</p>
                    <p><strong>Email:</strong> ${college.Email}</p>
                    <p><strong>Rating:</strong> ${college.rating}/5</p>
                `;
                document.getElementById('college-items').appendChild(collegeItem);
            }
        });
    }

    // Load the Google Maps JavaScript API dynamically
    function loadGoogleMapsAPI() {
        const script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key=<?php echo $apiKey; ?>&callback=initMap`;
        script.async = true;
        script.defer = true;
        script.onerror = () => {
            console.error('Failed to load Google Maps API.');
        };
        document.head.appendChild(script);
    }

    // Load the API when the page is ready
    window.onload = loadGoogleMapsAPI;
</script>
</body>
</html>