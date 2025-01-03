<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Student College Finder</title>
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
            height: 60vh;
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
        footer {
            background: var(--gradient-bg);
            color: #fff;
            text-align: center;
            padding: 20px 0;
            margin-top: auto;
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
            </div>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <h1>About Us</h1>
        <p>Discover our mission and values that drive us to help students find their dream colleges.</p>
    </div>
</section>

<!-- Mission Section -->
<section class="mission-section py-5">
    <div class="container">
        <h2 class="section-title">Our Mission</h2>
        <p class="section-subtitle">We are dedicated to empowering students to make informed decisions about their education.</p>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Empowering Students</h5>
                        <p class="card-text">We provide students with the tools and resources they need to find the best colleges for their goals. Our platform simplifies the college search process, making it easier for students to compare and apply to institutions that align with their aspirations.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Simplifying the Process</h5>
                        <p class="card-text">Our platform is designed to make the college search process seamless and stress-free. From detailed college profiles to personalized recommendations, we ensure students have all the information they need to make the right choice.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="values-section py-5 bg-light">
    <div class="container">
        <h2 class="section-title">Our Values</h2>
        <p class="section-subtitle">We are guided by these core principles in everything we do.</p>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Integrity</h5>
                        <p class="card-text">We are committed to honesty and transparency in all our interactions. Our platform provides accurate and unbiased information to help students.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Innovation</h5>
                        <p class="card-text">We constantly strive to improve and innovate our platform. By leveraging the latest technology, we ensure our users have access to the best tools and resources.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Student Focus</h5>
                        <p class="card-text">Our primary goal is to empower students to achieve their dreams. We are passionate about helping students find the right college and succeed in their academic journey.</p>
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
</body>
</html>