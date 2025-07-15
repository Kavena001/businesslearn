<?php
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
    'messages' => $db->getRow("SELECT COUNT(*) as count FROM messages WHERE status='unread'")['count'],
    'recent_enrollments' => $db->getRows("SELECT e.*, c.title as course_title 
                                         FROM enrollments e 
                                         JOIN courses c ON e.course_id = c.id 
                                         ORDER BY e.created_at DESC LIMIT 5")
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    
    <div class="container mt-4">
        <h2><i class="bi bi-speedometer2 me-2"></i> Tableau de Bord</h2>
        
        <div class="row mt-4">
            <!-- Courses Card -->
            <div class="col-md-4 mb-4">
                <div class="card text-white bg-primary h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Cours</h5>
                                <h2 class="mb-0"><?= $stats['courses'] ?></h2>
                            </div>
                            <i class="bi bi-book" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <a href="courses/list.php" class="text-white stretched-link">Voir tous les cours</a>
                    </div>
                </div>
            </div>
            
            <!-- Enrollments Card -->
            <div class="col-md-4 mb-4">
                <div class="card text-white bg-success h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Inscriptions</h5>
                                <h2 class="mb-0"><?= $stats['enrollments'] ?></h2>
                            </div>
                            <i class="bi bi-people" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <a href="enrollments/list.php" class="text-white stretched-link">Voir toutes les inscriptions</a>
                    </div>
                </div>
            </div>
            
            <!-- Messages Card -->
            <div class="col-md-4 mb-4">
                <div class="card text-white bg-warning h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Messages non lus</h5>
                                <h2 class="mb-0"><?= $stats['messages'] ?></h2>
                            </div>
                            <i class="bi bi-envelope" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <a href="messages/list.php" class="text-white stretched-link">Voir tous les messages</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Enrollments -->
        <div class="card mt-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i> Inscriptions récentes</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($stats['recent_enrollments'])): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Entreprise</th>
                                    <th>Cours</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats['recent_enrollments'] as $enrollment): ?>
                                <tr>
                                    <td><?= htmlspecialchars($enrollment['first_name'] . ' ' . $enrollment['last_name']) ?></td>
                                    <td><?= htmlspecialchars($enrollment['email']) ?></td>
                                    <td><?= htmlspecialchars($enrollment['company']) ?></td>
                                    <td><?= htmlspecialchars($enrollment['course_title']) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($enrollment['created_at'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end">
                        <a href="enrollments/list.php" class="btn btn-primary">Voir toutes les inscriptions</a>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">Aucune inscription récente.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>