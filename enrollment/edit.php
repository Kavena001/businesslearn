<?php
require '../includes/config.php';
require '../includes/db.php';
require '../includes/auth.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../admin/login.php');
    exit;
}

$enrollmentId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get enrollment data
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

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'first_name' => trim($_POST['first_name']),
        'last_name' => trim($_POST['last_name']),
        'email' => trim($_POST['email']),
        'phone' => trim($_POST['phone']),
        'company' => trim($_POST['company']),
        'position' => trim($_POST['position']),
        'employee_count' => trim($_POST['employee_count']),
        'payment_method' => trim($_POST['payment_method']),
        'status' => trim($_POST['status'])
    ];

    $db->update("
        UPDATE enrollment SET
            first_name = :first_name,
            last_name = :last_name,
            email = :email,
            phone = :phone,
            company = :company,
            position = :position,
            employee_count = :employee_count,
            payment_method = :payment_method,
            status = :status
        WHERE id = $enrollmentId
    ", $data);

    $_SESSION['message'] = "Inscription mise à jour avec succès";
    header("Location: view.php?id=$enrollmentId");
    exit;
}

include '../includes/header.php';
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-pencil-square me-2"></i> Modifier l'Inscription #<?= $enrollment['id'] ?></h2>
        <a href="view.php?id=<?= $enrollment['id'] ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Annuler
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Prénom *</label>
                        <input type="text" class="form-control" name="first_name" 
                               value="<?= htmlspecialchars($enrollment['first_name']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nom *</label>
                        <input type="text" class="form-control" name="last_name" 
                               value="<?= htmlspecialchars($enrollment['last_name']) ?>" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Email *</label>
                        <input type="email" class="form-control" name="email" 
                               value="<?= htmlspecialchars($enrollment['email']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Téléphone *</label>
                        <input type="tel" class="form-control" name="phone" 
                               value="<?= htmlspecialchars($enrollment['phone']) ?>" required>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label">Entreprise *</label>
                        <input type="text" class="form-control" name="company" 
                               value="<?= htmlspecialchars($enrollment['company']) ?>" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Poste *</label>
                        <input type="text" class="form-control" name="position" 
                               value="<?= htmlspecialchars($enrollment['position']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nombre d'employés</label>
                        <select class="form-select" name="employee_count">
                            <option value="1-10" <?= $enrollment['employee_count'] == '1-10' ? 'selected' : '' ?>>1-10</option>
                            <option value="11-50" <?= $enrollment['employee_count'] == '11-50' ? 'selected' : '' ?>>11-50</option>
                            <option value="51-200" <?= $enrollment['employee_count'] == '51-200' ? 'selected' : '' ?>>51-200</option>
                            <option value="201-500" <?= $enrollment['employee_count'] == '201-500' ? 'selected' : '' ?>>201-500</option>
                            <option value="500+" <?= $enrollment['employee_count'] == '500+' ? 'selected' : '' ?>>500+</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Méthode de paiement *</label>
                        <select class="form-select" name="payment_method" required>
                            <option value="card" <?= $enrollment['payment_method'] == 'card' ? 'selected' : '' ?>>Carte de crédit</option>
                            <option value="invoice" <?= $enrollment['payment_method'] == 'invoice' ? 'selected' : '' ?>>Facture</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Statut *</label>
                        <select class="form-select" name="status" required>
                            <option value="pending" <?= $enrollment['status'] == 'pending' ? 'selected' : '' ?>>En attente</option>
                            <option value="confirmed" <?= $enrollment['status'] == 'confirmed' ? 'selected' : '' ?>>Confirmé</option>
                            <option value="cancelled" <?= $enrollment['status'] == 'cancelled' ? 'selected' : '' ?>>Annulé</option>
                        </select>
                    </div>
                    
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save"></i> Enregistrer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>