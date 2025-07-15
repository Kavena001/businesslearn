<?php
require '../../includes/config.php';
require '../../includes/db.php';
require '../../includes/auth.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list.php');
    exit;
}

$messageId = (int)$_GET['id'];
$message = $db->getRow("SELECT * FROM messages WHERE id = ?", [$messageId]);

if (!$message) {
    header('Location: list.php');
    exit;
}

// Mark as read
$db->update("UPDATE messages SET status = 'read' WHERE id = ?", [$messageId]);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message de Contact</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <div class="container mt-4">
        <h2><i class="bi bi-envelope me-2"></i> Message de Contact</h2>
        
        <a href="list.php" class="btn btn-secondary mb-4">
            <i class="bi bi-arrow-left"></i> Retour à la liste
        </a>
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0"><?= htmlspecialchars($message['first_name'] . ' ' . $message['last_name']) ?></h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong>Email:</strong> <?= htmlspecialchars($message['email']) ?></p>
                        <p><strong>Téléphone:</strong> <?= htmlspecialchars($message['phone']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Entreprise:</strong> <?= htmlspecialchars($message['company']) ?></p>
                        <p><strong>Poste:</strong> <?= htmlspecialchars($message['position']) ?></p>
                    </div>
                </div>
                
                <div class="mb-4">
                    <h5>Sujet: <?= htmlspecialchars($message['interest']) ?></h5>
                </div>
                
                <div class="mb-4">
                    <h5>Message:</h5>
                    <div class="border p-3 bg-light">
                        <?= nl2br(htmlspecialchars($message['message'])) ?>
                    </div>
                </div>
                
                <div class="text-muted">
                    <small>Reçu le: <?= date('d/m/Y à H:i', strtotime($message['created_at'])) ?></small>
                </div>
            </div>
        </div>
        
        <div class="mt-3">
            <a href="mailto:<?= htmlspecialchars($message['email']) ?>" class="btn btn-success">
                <i class="bi bi-reply"></i> Répondre
            </a>
            <a href="delete.php?id=<?= $message['id'] ?>" class="btn btn-danger float-end" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?')">
                <i class="bi bi-trash"></i> Supprimer
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>