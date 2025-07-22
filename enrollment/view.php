<?php
require '../includes/config.php';
require '../includes/db.php';
require '../includes/auth.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../admin/login.php');
    exit;
}

$enrollmentId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$enrollment = $db->getRow("
    SELECT e.*, c.title as course_title
    FROM enrollment e
    JOIN courses c ON e.course_id = c.id
    WHERE e.id = ?
", [$enrollmentId]);

if (!$enrollment) {
    header('Location: list.php');
    exit;
}

include '../includes/header.php';
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-file-earmark-text me-2"></i> Détails de l'Inscription #<?= $enrollment['id'] ?></h2>
        <a href="list.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informations Personnelles</h5>
                </div>
                <div class="card-body">
                    <p><strong>Nom complet:</strong> <?= htmlspecialchars($enrollment['first_name'] . ' ' . $enrollment['last_name']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($enrollment['email']) ?></p>
                    <p><strong>Téléphone:</strong> <?= htmlspecialchars($enrollment['phone']) ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informations Professionnelles</h5>
                </div>
                <div class="card-body">
                    <p><strong>Entreprise:</strong> <?= htmlspecialchars($enrollment['company']) ?></p>
                    <p><strong>Poste:</strong> <?= htmlspecialchars($enrollment['position']) ?></p>
                    <p><strong>Nombre d'employés:</strong> <?= htmlspecialchars($enrollment['employee_count']) ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Détails de l'Inscription</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Cours:</strong> <?= htmlspecialchars($enrollment['course_title']) ?></p>
                    <p><strong>Méthode de paiement:</strong> <?= ucfirst($enrollment['payment_method']) ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Date d'inscription:</strong> <?= date('d/m/Y H:i', strtotime($enrollment['created_at'])) ?></p>
                    <p><strong>Statut:</strong> 
                        <span class="badge bg-<?= 
                            $enrollment['status'] == 'confirmed' ? 'success' : 
                            ($enrollment['status'] == 'cancelled' ? 'danger' : 'warning')
                        ?>">
                            <?= ucfirst($enrollment['status']) ?>
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end">
        <a href="edit.php?id=<?= $enrollment['id'] ?>" class="btn btn-warning me-2">
            <i class="bi bi-pencil"></i> Modifier
        </a>
        <?php if ($enrollment['status'] != 'confirmed'): ?>
        <a href="confirm.php?id=<?= $enrollment['id'] ?>" class="btn btn-success me-2">
            <i class="bi bi-check"></i> Confirmer
        </a>
        <?php endif; ?>
        <?php if ($enrollment['status'] != 'cancelled'): ?>
        <a href="cancel.php?id=<?= $enrollment['id'] ?>" class="btn btn-danger">
            <i class="bi bi-x"></i> Annuler
        </a>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>