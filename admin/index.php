<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Database connection
$conn = new mysqli("localhost", "root", "", "student_college_finder");
if ($conn->connect_error) {
    die("Connection failed: " . htmlspecialchars($conn->connect_error));
}

// Fetch users
$userQuery = "SELECT id, full_name, email, slc_passout_year, plus2_passout_year FROM users";
$userResult = $conn->query($userQuery);

// Fetch user activity data
$activityQuery = "
    SELECT DATE_FORMAT(activity_time, '%Y-%m') as month, COUNT(*) as activity_count 
    FROM user_activity 
    GROUP BY month 
    ORDER BY month";
$activityResult = $conn->query($activityQuery);

$activityData = [];
while ($row = $activityResult->fetch_assoc()) {
    $activityData[$row['month']] = $row['activity_count'];
}

$months = [];
$usageCounts = [];
for ($i = 12; $i >= 1; $i--) {
    $month = date('Y-m', strtotime("-$i months"));
    $months[] = $month;
    $usageCounts[] = isset($activityData[$month]) ? $activityData[$month] : 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Material Dashboard for managing users in the Student College Finder application.">
    <meta name="robots" content="noindex, nofollow">
    <title>Material Dashboard | User Management</title>
    <!-- MDBootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f5f5f5;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background-color: #3f51b5;
            color: white;
            padding: 20px;
        }
        .sidebar .nav-link {
            color: white;
            margin: 10px 0;
        }
        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #3f51b5;
            color: white;
            border-radius: 10px 10px 0 0;
        }
        .table {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h3 class="text-center mb-4">Admin Panel</h3>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="index.php">
                    <i class="fas fa-users me-2"></i>Manage Users
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1 class="mb-4">Dashboard</h1>

        <!-- Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Total Users</h5>
                    </div>
                    <div class="card-body">
                        <h3 class="card-text"><?= $userResult->num_rows; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Active Users</h5>
                    </div>
                    <div class="card-body">
                        <h3 class="card-text"><?= count($activityData); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Recent Activity</h5>
                    </div>
                    <div class="card-body">
                        <h3 class="card-text"><?= end($usageCounts); ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Manage Users</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>SLC Passout Year</th>
                            <th>+2 Passout Year</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = $userResult->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['id']); ?></td>
                                <td><?= htmlspecialchars($user['full_name']); ?></td>
                                <td><?= htmlspecialchars($user['email']); ?></td>
                                <td><?= htmlspecialchars($user['slc_passout_year']); ?></td>
                                <td><?= htmlspecialchars($user['plus2_passout_year']); ?></td>
                                <td>
                                    <a href="edit_user.php?id=<?= $user['id']; ?>" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="?delete_user=<?= $user['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- User Activity Chart -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title">User Activity Over Time</h5>
            </div>
            <div class="card-body">
                <canvas id="userActivityChart"></canvas>
            </div>
        </div>
    </div>

    <!-- MDBootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>
    <script>
        // User Activity Chart
        const ctx = document.getElementById('userActivityChart').getContext('2d');
        const userActivityChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($months); ?>,
                datasets: [{
                    label: 'Users Active',
                    data: <?= json_encode($usageCounts); ?>,
                    backgroundColor: 'rgba(63, 81, 181, 0.6)',
                    borderColor: 'rgba(63, 81, 181, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>