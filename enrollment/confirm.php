<?php
require '../includes/config.php';
require '../includes/db.php';
require '../includes/auth.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../admin/login.php');
    exit;
}

$enrollmentId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($enrollmentId > 0) {
    // Update status to confirmed
    $db->update("UPDATE enrollment SET status = 'confirmed' WHERE id = ?", [$enrollmentId]);
    
    // Get enrollment details for notification
    $enrollment = $db->getRow("
        SELECT e.*, c.title as course_title 
        FROM enrollment e
        JOIN courses c ON e.course_id = c.id
        WHERE e.id = ?
    ", [$enrollmentId]);
    
    // Send confirmation email (implementation depends on your mail setup)
    // sendConfirmationEmail($enrollment);
    
    $_SESSION['message'] = "Inscription #$enrollmentId confirmée avec succès";
}

header('Location: list.php');
exit;
?>