<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate a secure random token
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration - Student College Finder</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2ecc71;
            --background: #ecf0f3;
            --card-bg: #ffffff;
            --text-primary: #2c3e50;
            --shadow-dark: rgba(0, 0, 0, 0.1);
            --shadow-light: rgba(255, 255, 255, 0.5);
        }

        body {
            background-color: var(--background);
            font-family: 'Poppins', sans-serif;
            color: var(--text-primary);
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .navbar {
            background: var(--card-bg);
            box-shadow: 0 4px 6px var(--shadow-dark);
            padding: 1rem 0;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .navbar-brand, .nav-link {
            color: var(--text-primary) !important;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .navbar-brand:hover, .nav-link:hover {
            color: var(--primary-color) !important;
        }

        .login-card {
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: 8px 8px 15px var(--shadow-dark), -8px -8px 15px var(--shadow-light);
            width: 100%;
            max-width: 600px;
            padding: 2rem;
            margin-top: 4rem;
        }

        .login-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .login-header h4 {
            color: var(--primary-color);
        }

        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
        }

        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-control {
            background: var(--background);
            border: 1px solid var(--shadow-dark);
            border-radius: 10px;
            padding: 0.75rem 0.75rem 0.75rem 40px;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: inset 4px 4px 6px var(--shadow-dark), inset -4px -4px 6px var(--shadow-light);
        }

        .form-control:focus {
            box-shadow: inset 4px 4px 6px var(--shadow-dark), inset -4px -4px 6px var(--shadow-light), 0 0 5px var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-label {
            position: absolute;
            top: 12px;
            left: 40px;
            background: var(--card-bg);
            padding: 0 5px;
            font-size: 14px;
            color: var(--text-primary);
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .form-control:focus + .form-label,
        .form-control:not(:placeholder-shown) + .form-label {
            top: -10px;
            font-size: 12px;
            color: var(--primary-color);
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: var(--text-primary);
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--text-primary);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            border-radius: 10px;
            padding: 0.75rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: 4px 4px 6px var(--shadow-dark), -4px -4px 6px var(--shadow-light);
        }

        .btn-primary:hover {
            background-color: #27ae60;
            transform: translateY(-2px);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-secondary {
            background-color: var(--background);
            border: none;
            border-radius: 10px;
            padding: 0.75rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: 4px 4px 6px var(--shadow-dark), -4px -4px 6px var(--shadow-light);
        }

        .btn-secondary:hover {
            background-color: #d5d5d5;
            transform: translateY(-2px);
        }

        .btn-secondary:active {
            transform: translateY(0);
        }

        .form-navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 1.5rem;
        }

        .file-input {
            position: relative;
            overflow: hidden;
        }

        .file-input input[type="file"] {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 100px;
            text-align: right;
            opacity: 0;
            outline: none;
            cursor: pointer;
        }

        .file-input-label {
            display: block;
            background: var(--background);
            border: 1px solid var(--shadow-dark);
            border-radius: 10px;
            padding: 0.75rem;
            font-size: 1rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 4px 4px 6px var(--shadow-dark), -4px -4px 6px var(--shadow-light);
        }

        .file-input-label:hover {
            border-color: var(--primary-color);
        }

        @media (max-width: 767.98px) {
            .login-card {
                margin: 1rem;
            }
        }
        .file-added {
            color: var(--primary-color);
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: none;
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
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>    
            </ul>
        </div>
    </div>
</nav>

<!-- Registration Form Section -->
<div class="login-card">
    <div class="login-header">
        <h4><i class="fas fa-user-plus"></i> Student Registration</h4>
    </div>
    <form action="register_process.php" method="POST" enctype="multipart/form-data">
        <!-- CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <!-- Step 1: Personal Information -->
        <div class="form-step active" id="step1">
            <fieldset class="mb-4">
                <legend>Personal Information</legend>
                <div class="form-group">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" class="form-control" id="fullName" name="fullName" placeholder=" " required>
                    <label for="fullName" class="form-label">Full Name</label>
                </div>
                <div class="form-group">
                    <i class="fas fa-user-friends input-icon"></i>
                    <input type="text" class="form-control" id="fatherName" name="fatherName" placeholder=" " required>
                    <label for="fatherName" class="form-label">Father's Name</label>
                </div>
                <div class="form-group">
                    <i class="fas fa-user-friends input-icon"></i>
                    <input type="text" class="form-control" id="motherName" name="motherName" placeholder=" " required>
                    <label for="motherName" class="form-label">Mother's Name</label>
                </div>
                <div class="form-group">
                    <i class="fas fa-phone input-icon"></i>
                    <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" placeholder=" " required>
                    <label for="phoneNumber" class="form-label">Phone Number</label>
                </div>
            </fieldset>
            <div class="form-navigation">
                <button type="button" class="btn btn-secondary" disabled>Previous</button>
                <button type="button" class="btn btn-primary" onclick="nextStep(2)">Next</button>
            </div>
        </div>

        <!-- Step 2: Educational Details -->
        <div class="form-step" id="step2">
            <fieldset class="mb-4">
                <legend>Educational Details</legend>
                <div class="form-group">
                    <i class="fas fa-calendar-alt input-icon"></i>
                    <input type="number" class="form-control" id="slcPassoutYear" name="slcPassoutYear" placeholder=" " required>
                    <label for="slcPassoutYear" class="form-label">SLC Passout Year</label>
                </div>
                <div class="form-group">
                    <i class="fas fa-calendar-alt input-icon"></i>
                    <input type="number" class="form-control" id="plus2PassoutYear" name="plus2PassoutYear" placeholder=" " required>
                    <label for="plus2PassoutYear" class="form-label">+2 Passout Year</label>
                </div>
            </fieldset>
            <div class="form-navigation">
                <button type="button" class="btn btn-secondary" onclick="prevStep(1)">Previous</button>
                <button type="button" class="btn btn-primary" onclick="nextStep(3)">Next</button>
            </div>
        </div>

        <div class="form-step" id="step3">
            <div class="form-group">
                <i class="fas fa-envelope input-icon"></i>
                <input type="email" class="form-control" id="email" name="email" placeholder=" " required>
                <label for="email" class="form-label">Email Address</label>
            </div>
            <div class="form-group">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" class="form-control" id="password" name="password" placeholder=" " required>
                <label for="password" class="form-label">Password</label>
                <span class="password-toggle" onclick="togglePassword()">
                    <i class="fas fa-eye"></i>
                </span>
            </div>
            <div class="form-group">
                <div class="file-input">
                    <input type="file" class="form-control" id="seeCertificate" name="seeCertificate" required onchange="showFileAdded('seeCertificate')">
                    <label for="seeCertificate" class="file-input-label">
                        <i class="fas fa-upload"></i> Upload SEE Certificate
                    </label>
                    <span id="seeCertificateAdded" class="file-added">File added!</span>
                </div>
            </div>
            <div class="form-group">
                <div class="file-input">
                    <input type="file" class="form-control" id="plus12Certificate" name="plus12Certificate" required onchange="showFileAdded('plus12Certificate')">
                    <label for="plus12Certificate" class="file-input-label">
                        <i class="fas fa-upload"></i> Upload +2 Certificate
                    </label>
                    <span id="plus12CertificateAdded" class="file-added">File added!</span>
                </div>
            </div>
            <div class="form-navigation">
                <button type="button" class="btn btn-secondary" onclick="prevStep(2)">Previous</button>
                <button type="submit" class="btn btn-primary">Register</button>
            </div>
        </div>
    </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    // Multi-Step Form Navigation
    function nextStep(step) {
        document.querySelector('.form-step.active').classList.remove('active');
        document.getElementById(`step${step}`).classList.add('active');
    }

    function prevStep(step) {
        document.querySelector('.form-step.active').classList.remove('active');
        document.getElementById(`step${step}`).classList.add('active');
    }

    // Toggle Password Visibility
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.querySelector('.password-toggle i');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }

    // Show "File added" message
    function showFileAdded(inputId) {
        const fileAddedMessage = document.getElementById(`${inputId}Added`);
        if (fileAddedMessage) {
            fileAddedMessage.style.display = 'block';
        }
    }
</script>

</body>
</html>