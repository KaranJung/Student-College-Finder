
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Student College Finder</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2ecc71;
            --background: #ecf0f3;
            --card-bg: #ecf0f3;
            --text-primary: #2c3e50;
            --shadow-dark: rgba(0, 0, 0, 0.2);
            --shadow-light: rgba(255, 255, 255, 0.8);
        }

        body {
            background-color: var(--background);
            font-family: 'Poppins', sans-serif;
            color: var(--text-primary);
            margin: 0;
            min-height: 100vh;
        }

        /* Navbar Styles */
        .navbar {
            background: var(--card-bg);
            box-shadow: 8px 8px 15px var(--shadow-dark), -8px -8px 15px var(--shadow-light);
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

        .navbar-toggler {
            border: none;
            padding: 0.5rem;
        }

        .navbar-toggler:focus {
            box-shadow: none;
            outline: none;
        }

        .btn-logout {
            background-color: var(--primary-color);
            color: white;
            border-radius: 20px;
            padding: 0.5rem 1.5rem;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background-color: #27ae60;
            color: white;
            transform: translateY(-2px);
        }

        /* Login Card Styles */
        .main-content {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 76px); /* Subtract navbar height */
            padding: 2rem 0;
        }

        .login-card {
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: 20px 20px 60px var(--shadow-dark), -20px -20px 60px var(--shadow-light);
            width: 400px;
            padding: 40px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .login-header h4 {
            color: var(--primary-color);
        }

        .form-control {
            background: var(--card-bg);
            border: none;
            border-radius: 30px;
            box-shadow: inset 6px 6px 10px var(--shadow-dark), inset -6px -6px 10px var(--shadow-light);
            padding: 12px;
            font-size: 16px;
        }

        .form-control:focus {
            outline: none;
            box-shadow: inset 6px 6px 10px var(--shadow-dark), inset -6px -6px 10px var(--shadow-light), 0 0 5px var(--primary-color);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            border-radius: 30px;
            box-shadow: 6px 6px 10px var(--shadow-dark), -6px -6px 10px var(--shadow-light);
            padding: 12px;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #27ae60;
        }

        .btn-primary:active {
            background-color: #219653;
            box-shadow: inset 4px 4px 6px var(--shadow-dark), inset -4px -4px 6px var(--shadow-light);
        }

        .footer-text {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .footer-text a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .footer-text a:hover {
            text-decoration: underline;
        }

        @media (max-width: 991.98px) {
            .navbar-collapse {
                padding: 1rem 0;
            }
            
            .navbar-nav {
                margin-bottom: 1rem;
            }
            
            .d-flex {
                justify-content: center;
            }

            .login-card {
                width: 90%;
                margin: 0 20px;
            }
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
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    <!-- Login Card -->
    <div class="main-content">
        <div class="login-card">
            <div class="login-header">
                <h4><i class="fas fa-user-circle"></i> Login</h4>
            </div>
            <form action="login_process.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
            <div class="footer-text">
                <p>Don't have an account? <a href="register.php">Register here</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
   
</body>
</html>