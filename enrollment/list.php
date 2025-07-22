<?php
require '../includes/config.php';
require '../includes/db.php';
require '../includes/auth.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../admin/login.php');
    exit;
}

// Get all enrollments with course and user info
$enrollments = $db->getRows("
    SELECT e.*, c.title as course_title, 
           CONCAT(e.first_name, ' ', e.last_name) as student_name
    FROM enrollment e
    JOIN courses c ON e.course_id = c.id
    ORDER BY e.created_at DESC
");

include '../includes/header.php';
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-list-check me-2"></i> Gestion des Inscriptions</h2>
        <a href="../admin/dashboard.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Étudiant</th>
                            <th>Cours</th>
                            <th>Entreprise</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($enrollments as $enrollment): ?>
                        <tr>
                            <td><?= $enrollment['id'] ?></td>
                            <td><?= htmlspecialchars($enrollment['student_name']) ?></td>
                            <td><?= htmlspecialchars($enrollment['course_title']) ?></td>
                            <td><?= htmlspecialchars($enrollment['company']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($enrollment['created_at'])) ?></td>
                            <td>
                                <span class="badge bg-<?= 
                                    $enrollment['status'] == 'confirmed' ? 'success' : 
                                    ($enrollment['status'] == 'cancelled' ? 'danger' : 'warning')
                                ?>">
                                    <?= ucfirst($enrollment['status']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="view.php?id=<?= $enrollment['id'] ?>" 
                                       class="btn btn-primary" title="Voir">
                                       <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="edit.php?id=<?= $enrollment['id'] ?>" 
                                       class="btn btn-warning" title="Modifier">
                                       <i class="bi bi-pencil"></i>
                                    </a>
                                    <?php if ($enrollment['status'] != 'confirmed'): ?>
                                    <a href="confirm.php?id=<?= $enrollment['id'] ?>" 
                                       class="btn btn-success" title="Confirmer">
                                       <i class="bi bi-check"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php if ($enrollment['status'] != 'cancelled'): ?>
                                    <a href="cancel.php?id=<?= $enrollment['id'] ?>" 
                                       class="btn btn-danger" title="Annuler">
                                       <i class="bi bi-x"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>