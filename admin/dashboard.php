<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

require_once '../includes/db.php';

// Get stats for dashboard
$totalCourses = $db->getRow("SELECT COUNT(*) as count FROM courses")['count'];
$totalEnrollments = $db->getRow("SELECT COUNT(*) as count FROM enrollments")['count'];
$totalMessages = $db->getRow("SELECT COUNT(*) as count FROM messages WHERE status = 'unread'")['count'];
$recentEnrollments = $db->getRows("SELECT e.*, c.title as course_title 
                                  FROM enrollments e 
                                  JOIN courses c ON e.course_id = c.id 
                                  ORDER BY e.created_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord | <?php echo SITE_TITLE; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    
    <div class="container-fluid py-4">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Tableau de bord</h1>
                </div>
                
                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title">Cours</h5>
                                        <h2 class="mb-0"><?php echo $totalCourses; ?></h2>
                                    </div>
                                    <i class="bi bi-book" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                            <div class="card-footer bg-primary-dark">
                                <a href="courses/list.php" class="text-white">Voir tous les cours <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card text-white bg-success mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title">Inscriptions</h5>
                                        <h2 class="mb-0"><?php echo $totalEnrollments; ?></h2>
                                    </div>
                                    <i class="bi bi-people" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                            <div class="card-footer bg-success-dark">
                                <a href="enrollments/list.php" class="text-white">Voir les inscriptions <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card text-white bg-warning mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title">Messages non lus</h5>
                                        <h2 class="mb-0"><?php echo $totalMessages; ?></h2>
                                    </div>
                                    <i class="bi bi-envelope" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                            <div class="card-footer bg-warning-dark">
                                <a href="messages/list.php" class="text-white">Voir les messages <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Enrollments -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Inscriptions récentes</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Cours</th>
                                        <th>Entreprise</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentEnrollments as $enrollment): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($enrollment['first_name'] . ' ' . $enrollment['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($enrollment['course_title']); ?></td>
                                        <td><?php echo htmlspecialchars($enrollment['company']); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($enrollment['created_at'])); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>