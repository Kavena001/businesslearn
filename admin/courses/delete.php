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

$courseId = (int)$_GET['id'];
$course = $db->getRow("SELECT * FROM courses WHERE id = ?", [$courseId]);

if (!$course) {
    header('Location: list.php');
    exit;
}

// Delete the course image if it exists
if ($course['image']) {
    $imagePath = '../../uploads/courses/' . $course['image'];
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}

// Delete the course from database
$db->update("DELETE FROM courses WHERE id = ?", [$courseId]);

$_SESSION['message'] = "Le cours a été supprimé avec succès!";
$_SESSION['message_type'] = 'success';
header('Location: list.php');
exit;
?>