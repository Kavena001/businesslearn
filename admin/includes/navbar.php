<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="../dashboard.php">Tableau de Bord</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>" href="../dashboard.php">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= strpos($_SERVER['REQUEST_URI'], '/courses/') !== false ? 'active' : '' ?>" href="#" id="coursesDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-book"></i> Cours
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../courses/list.php">Liste des cours</a></li>
                        <li><a class="dropdown-item" href="../courses/add.php">Ajouter un cours</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/enrollments/') !== false ? 'active' : '' ?>" href="../enrollments/list.php">
                        <i class="bi bi-people"></i> Inscriptions
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/messages/') !== false ? 'active' : '' ?>" href="../messages/list.php">
                        <i class="bi bi-envelope"></i> Messages
                        <?php 
                        $unreadCount = $db->getRow("SELECT COUNT(*) as count FROM messages WHERE status = 'unread'")['count'];
                        if ($unreadCount > 0): ?>
                            <span class="badge bg-danger"><?= $unreadCount ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            </ul>
            <div class="d-flex">
                <span class="navbar-text me-3">
                    Connecté en tant que: <strong><?= htmlspecialchars($_SESSION['admin_name']) ?></strong>
                </span>
                <a href="../logout.php" class="btn btn-outline-light">
                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                </a>
            </div>
        </div>
    </div>
</nav>

<div style="height: 70px;"></div> <!-- Spacer for fixed navbar -->