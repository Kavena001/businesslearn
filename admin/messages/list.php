<?php
require '../../includes/config.php';
require '../../includes/db.php';
require '../../includes/auth.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../login.php');
    exit;
}

// Mark message as read if ID is provided
if (isset($_GET['mark_read']) {
    $messageId = (int)$_GET['mark_read'];
    $db->update("UPDATE messages SET status = 'read' WHERE id = ?", [$messageId]);
}

// Get all messages
$messages = $db->getRows("SELECT * FROM messages ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages des Contacts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <div class="container mt-4">
        <h2><i class="bi bi-envelope me-2"></i> Messages des Contacts</h2>
        
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['message_type'] ?>">
                <?= $_SESSION['message'] ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Entreprise</th>
                        <th>Sujet</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $message): ?>
                    <tr>
                        <td><?= $message['id'] ?></td>
                        <td><?= htmlspecialchars($message['first_name'] . ' ' . $message['last_name']) ?></td>
                        <td><?= htmlspecialchars($message['email']) ?></td>
                        <td><?= htmlspecialchars($message['company']) ?></td>
                        <td><?= htmlspecialchars($message['interest']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($message['created_at'])) ?></td>
                        <td>
                            <span class="badge bg-<?= $message['status'] === 'unread' ? 'warning' : 'success' ?>">
                                <?= $message['status'] === 'unread' ? 'Non lu' : 'Lu' ?>
                            </span>
                        </td>
                        <td>
                            <a href="view.php?id=<?= $message['id'] ?>" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye"></i> Voir
                            </a>
                            <a href="delete.php?id=<?= $message['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?')">
                                <i class="bi bi-trash"></i> Supprimer
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>