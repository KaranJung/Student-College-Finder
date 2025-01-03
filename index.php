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
            --primary-color: #6c5ce7; /* Soft Purple */
            --secondary-color: #00b894; /* Soft Green */
            --background: #f9f9f9; /* Light Background */
            --card-bg: #ffffff; /* White Card Background */
            --text-primary: #2d3436; /* Dark Text */
            --text-secondary: #636e72; /* Gray Text */
            --shadow-color: rgba(0, 0, 0, 0.1);
            --gradient-bg: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }
        body {
            background-color: var(--background);
            font-family: 'Poppins', sans-serif;
            color: var(--text-primary);
            margin: 0;
            min-height: 100vh;
            overflow-x: hidden;
            scroll-behavior: smooth;
        }
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        ::-webkit-scrollbar-track {
            background: var(--background);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--secondary-color);
        }
        .navbar {
            background: var(--card-bg);
            box-shadow: 0 4px 12px var(--shadow-color);
            padding: 1rem 0;
        }
        .navbar-brand, .nav-link {
            color: var(--text-primary) !important;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .navbar-brand:hover, .nav-link:hover {
            color: var(--primary-color) !important;
        }
        .hero-section {
            height: 100vh;
            background: var(--gradient-bg);
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
            z-index: 1;
        }
        .hero-content {
            position: relative;
            z-index: 2;
        }
        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .hero-content p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
        .btn-custom {
            background-color: var(--primary-color);
            color: #fff;
            border-radius: 50px;
            transition: background-color 0.3s, transform 0.3s;
            padding: 10px 20px;
            font-weight: 600;
        }
        .btn-custom:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }
        .search-bar {
            position: relative;
            margin: 20px 0;
            max-width: 500px;
            width: 100%;
            margin-left: auto;
            margin-right: auto;
        }
        .search-bar input {
            background: var(--card-bg);
            border: 2px solid var(--primary-color);
            color: var(--text-primary);
            border-radius: 50px;
            padding: 10px 20px;
            transition: border-color 0.3s;
        }
        .search-bar input:focus {
            border-color: var(--secondary-color);
            outline: none;
        }
        .search-bar input::placeholder {
            color: var(--text-secondary);
        }
        .suggestions-list {
            border: 2px solid var(--primary-color);
            background: var(--card-bg);
            position: absolute;
            z-index: 1000;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            display: none;
            border-radius: 10px;
            margin-top: 5px;
        }
        .suggestion-item {
            padding: 12px 15px;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.2s;
            font-size: 14px;
            color: var(--text-primary);
            border-bottom: 1px solid var(--primary-color);
        }
        .suggestion-item:last-child {
            border-bottom: none;
        }
        .suggestion-item:hover {
            background-color: rgba(108, 92, 231, 0.1);
            transform: translateX(5px);
        }
        .section-title {
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
            color: var(--primary-color);
        }
        .section-subtitle {
            font-size: 1.2rem;
            color: var(--text-secondary);
            text-align: center;
            margin-bottom: 40px;
        }
        .card {
            background: var(--card-bg);
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 12px var(--shadow-color);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(108, 92, 231, 0.2);
        }
        .card-body {
            padding: 20px;
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--primary-color);
        }
        .card-text {
            font-size: 1rem;
            color: var(--text-secondary);
        }
        .top-provinces-section .card {
            background: var(--card-bg);
            border: 2px solid var(--secondary-color);
        }
        .top-provinces-section .card-title {
            color: var(--secondary-color);
        }
        .top-provinces-section .card-text {
            color: var(--text-secondary);
        }
        .table-responsive {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 12px var(--shadow-color);
        }
        .table thead {
            background: var(--primary-color);
            color: #fff;
        }
        .table tbody tr {
            transition: background-color 0.3s;
        }
        .table tbody tr:hover {
            background-color: rgba(108, 92, 231, 0.1);
        }
        .rank-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 600;
            color: #fff;
            text-align: center;
            min-width: 40px;
        }
        .rank-badge.rank-1 {
            background: linear-gradient(135deg, #ff9a9e, #fad0c4);
        }
        .rank-badge.rank-2 {
            background: linear-gradient(135deg, #a1c4fd, #c2e9fb);
        }
        .rank-badge.rank-3 {
            background: linear-gradient(135deg, #84fab0, #8fd3f4);
        }
        .rank-badge.rank-4 {
            background: linear-gradient(135deg, #fbc2eb, #a6c1ee);
        }
        .rank-badge.rank-5 {
            background: linear-gradient(135deg, #ffecd2, #fcb69f);
        }
        footer {
            background: var(--gradient-bg);
            color: #fff;
            text-align: center;
            padding: 20px 0;
            margin-top: auto;
        }
        .course-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .course-tag {
            display: inline-flex;
            align-items: center;
            background-color: #6c5ce7;
            color: #fff;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.9rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .course-tag:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .course-tag i {
            margin-right: 5px;
        }
        /* Custom Arrow Styles */
        .custom-arrow {
            margin-left: 20px; /* Space between button and arrow */
            width: 50px;
            height: 50px;
            background-color: var(--primary-color);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s, transform 0.3s;
            animation: bounce 2s infinite, highlight 3s ease-in-out;
        }
        .custom-arrow:hover {
            background-color: var(--secondary-color);
            transform: scale(1.1);
        }
        .custom-arrow i {
            font-size: 1.5rem;
            color: #fff;
        }
        /* Bounce Animation */
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }
        /* Highlight Animation */
        @keyframes highlight {
            0% {
                box-shadow: 0 0 0 0 rgba(108, 92, 231, 0.4);
            }
            50% {
                box-shadow: 0 0 0 20px rgba(108, 92, 231, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(108, 92, 231, 0);
            }
        }
        /* Flex container for button and arrow */
        .button-arrow-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 30px;
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
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
            </ul>
            <div class="d-flex">
                <a class="nav-link me-3" href="login.php">Login</a>
                <a class="nav-link me-3" href="register.php">Register</a>
                <a class="nav-link me-3" href="toplist.php">Top Colleges</a>
                <a class="nav-link me-3" href="aboutus.php">About Us</a>
            </div>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <h1>Discover Your Dream College</h1>
        <p>Explore colleges that match your interests and aspirations.</p>
        
        <!-- Search Bar -->
        <div class="search-bar">
            <input type="text" class="form-control" id="collegeSearch" placeholder="Search for colleges..." aria-label="Search">
            <div id="suggestions" class="suggestions-list"></div>
        </div>
        
        <!-- Button and Arrow Container -->
        <div class="button-arrow-container">
            <a href="login.php" class="btn btn-custom">Get Started <i class="fas fa-arrow-right ms-2"></i></a>
            <!-- Custom Arrow -->
            <div class="custom-arrow">
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </div>
</section>

<!-- Top 5 Colleges Section -->
<section class="top-colleges-section py-5">
    <div class="container">
        <h2 class="section-title">Top 5 Highest Rated Colleges</h2>
        <p class="section-subtitle">Explore the top-rated colleges based on real data and user reviews.</p>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>College</th>
                        <th>Rating</th>
                        <th>Location</th>
                        <th>University</th>
                        <th>Courses Offered</th>
                    </tr>
                </thead>
                <tbody id="colleges-table-body">
                    <!-- Top 5 colleges will be dynamically inserted here -->
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Top Provinces for Education Section -->
<section class="top-provinces-section py-5">
    <div class="container">
        <h2 class="section-title">Top Provinces for Education</h2>
        <p class="section-subtitle">Discover the top provinces known for their educational institutions.</p>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-university me-2"></i>Bagmati</h5>
                        <p class="card-text">Home to some of the most prestigious institutions in Nepal.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-flask me-2"></i>Gandaki</h5>
                        <p class="card-text">Known for its top engineering and medical colleges.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-book me-2"></i>Lumbini</h5>
                        <p class="card-text">Famous for its universities and research institutions.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-school me-2"></i>Madhesh</h5>
                        <p class="card-text">A hub for education with numerous colleges and universities.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-graduation-cap me-2"></i>Karnali</h5>
                        <p class="card-text">Offers a wide range of educational opportunities.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-microscope me-2"></i>Koshi</h5>
                        <p class="card-text">Known for its engineering and medical colleges.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    <p>&copy; 2024 Student College Finder. All rights reserved.</p>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

<script>
    let colleges = []; // Array to store fetched college data

    // Function to fetch college data
    async function fetchColleges() {
        try {
            const response = await fetch('updated_colleges_nepal.json'); // Use forward slashes
            if (!response.ok) {
                throw new Error('Failed to fetch colleges');
            }
            colleges = await response.json(); // Store fetched data in the colleges array
            console.log('Colleges loaded successfully:', colleges); // Debugging
            displayTopColleges(); // Display top 5 colleges after fetching
        } catch (error) {
            console.error('Error fetching colleges:', error);
            colleges = []; // Reset colleges in case of error
        }
    }

    // Function to get a random color for course tags
    function getRandomColor() {
        const colors = ['#6c5ce7', '#00b894', '#e84393', '#0984e3', '#fdcb6e', '#d63031'];
        return colors[Math.floor(Math.random() * colors.length)];
    }

    // Function to get an icon based on course type
    function getCourseIcon(course) {
        if (course.toLowerCase().includes('engineering')) return '🔧';
        if (course.toLowerCase().includes('medical')) return '🏥';
        if (course.toLowerCase().includes('computer')) return '💻';
        if (course.toLowerCase().includes('business')) return '📊';
        return '🎓'; // Default icon
    }

    // Function to display top 5 colleges
    function displayTopColleges() {
        const tableBody = document.getElementById('colleges-table-body');
        tableBody.innerHTML = '';

        // Sort colleges by rating in descending order
        const sortedColleges = colleges.sort((a, b) => b.rating - a.rating).slice(0, 5);

        sortedColleges.forEach((college, index) => {
            const row = document.createElement('tr');
            const courses = college['Course Offered'].split('\n').filter(course => course.trim() !== ''); // Split courses by newline
            const courseTags = document.createElement('div');
            courseTags.className = 'course-tags';

            // Create a tag for each course
            courses.forEach(course => {
                const tag = document.createElement('span');
                tag.className = 'course-tag';
                tag.style.backgroundColor = getRandomColor(); // Assign a random color
                tag.innerHTML = `<i>${getCourseIcon(course)}</i> ${course}`; // Add icon and course name
                courseTags.appendChild(tag);
            });

            row.innerHTML = `
                <td><span class="rank-badge rank-${index + 1}">#${index + 1}</span></td>
                <td>${college.College}</td>
                <td>${college.rating}</td>
                <td>${college.Location}</td>
                <td>${college.University}</td>
                <td></td>
            `;
            row.querySelector('td:last-child').appendChild(courseTags); // Append the course tags
            tableBody.appendChild(row);
        });
    }

    // Debounce function to limit the rate of search input handling
    function debounce(func, delay) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), delay);
        };
    }

    // Function to handle search input
    const handleSearchInput = debounce(function() {
        const query = this.value.toLowerCase();
        const suggestions = document.getElementById('suggestions');
        suggestions.innerHTML = '';

        if (query.length > 0) {
            const filteredColleges = colleges.filter(college => 
                college.College.toLowerCase().includes(query)
            );

            if (filteredColleges.length > 0) {
                suggestions.style.display = 'block';
                filteredColleges.forEach(college => {
                    const suggestionItem = document.createElement('div');
                    suggestionItem.textContent = college.College;
                    suggestionItem.className = 'suggestion-item';
                    suggestionItem.addEventListener('click', function() {
                        // Redirect to the login page
                        window.location.href = 'login.php';
                    });
                    suggestions.appendChild(suggestionItem);
                });
            } else {
                suggestions.style.display = 'none';
            }
        } else {
            suggestions.style.display = 'none';
        }
    }, 300);

    // Initialize the page
    async function initialize() {
        await fetchColleges(); // Load colleges
        document.getElementById('collegeSearch').addEventListener('input', handleSearchInput); // Add search input listener
    }

    // Call initialize when the page loads
    window.onload = initialize;

    // Close suggestions when clicking outside
    document.addEventListener('click', function(event) {
        const suggestions = document.getElementById('suggestions');
        const searchInput = document.getElementById('collegeSearch');
        if (event.target !== searchInput && !searchInput.contains(event.target)) {
            suggestions.style.display = 'none';
        }
    });

    // Smooth scroll to the next section
    document.querySelector('.custom-arrow').addEventListener('click', function() {
        const nextSection = document.querySelector('.top-colleges-section');
        nextSection.scrollIntoView({ behavior: 'smooth' });
    });
</script>

</body>
</html>