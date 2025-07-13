<?php
session_start();
require '../includes/config.php';
require '../includes/db.php';
require '../includes/auth.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get stats
$stats = [
    'courses' => $db->getRow("SELECT COUNT(*) as count FROM courses")['count'],
    'enrollments' => $db->getRow("SELECT COUNT(*) as count FROM enrollments")['count'],
    'messages' => $db->getRow("SELECT COUNT(*) as count FROM messages WHERE status='unread'")['count']
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            padding-top: 60px;
            background-color: #f8f9fa;
        }
        .sidebar {
            position: fixed;
            top: 60px;
            left: 0;
            bottom: 0;
            width: 250px;
            background: #343a40;
            color: white;
            padding: 20px 0;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .stat-card {
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Dashboard</a>
            <div class="d-flex">
                <span class="text-white me-3">Welcome, <?= htmlspecialchars($_SESSION['admin_name']) ?></span>
                <a href="logout.php" class="btn btn-outline-light">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="dashboard.php">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="courses/">
                    <i class="bi bi-book me-2"></i> Courses
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="enrollments/">
                    <i class="bi bi-people me-2"></i> Enrollments
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="messages/">
                    <i class="bi bi-envelope me-2"></i> Messages
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2 class="mb-4">Dashboard Overview</h2>
        
        <div class="row">
            <!-- Courses Stat -->
            <div class="col-md-4 mb-4">
                <div class="card stat-card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Courses</h5>
                                <h2><?= $stats['courses'] ?></h2>
                            </div>
                            <i class="bi bi-book" style="font-size: 2rem;"></i>
                        </div>
                        <a href="courses/" class="text-white stretched-link"></a>
                    </div>
                </div>
            </div>
            
            <!-- Enrollments Stat -->
            <div class="col-md-4 mb-4">
                <div class="card stat-card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Enrollments</h5>
                                <h2><?= $stats['enrollments'] ?></h2>
                            </div>
                            <i class="bi bi-people" style="font-size: 2rem;"></i>
                        </div>
                        <a href="enrollments/" class="text-white stretched-link"></a>
                    </div>
                </div>
            </div>
            
            <!-- Messages Stat -->
            <div class="col-md-4 mb-4">
                <div class="card stat-card bg-warning text-dark">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5 class="card-title">Unread Messages</h5>
                                <h2><?= $stats['messages'] ?></h2>
                            </div>
                            <i class="bi bi-envelope" style="font-size: 2rem;"></i>
                        </div>
                        <a href="messages/" class="text-dark stretched-link"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>