<?php
require_once 'config.php';
require_once 'db.php';

// Get site settings
$settings = $db->getRows("SELECT * FROM settings");
$siteSettings = [];
foreach ($settings as $setting) {
    $siteSettings[$setting['setting_key']] = $setting['setting_value']; // Fixed the array assignment syntax
}

// Get featured courses for navigation
$featuredCourses = $db->getRows("SELECT id, title FROM courses WHERE featured = 1 ORDER BY title LIMIT 3");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo (isset($pageTitle) ? $pageTitle . ' | ' : '') . htmlspecialchars($siteSettings['site_title'] ?? 'Formation Professionnelle'); ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <link href="<?php echo SITE_URL; ?>/css/style.css" rel="stylesheet">
    
    <style>
        /* Ensure carousel displays properly */
        .carousel {
            overflow: hidden;
        }
        .carousel-inner {
            height: 400px;
        }
        .carousel-item img {
            object-fit: cover;
            width: 100%;
            height: 100%;
        }
        .carousel-caption {
            background-color: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 5px;
        }
        
        /* Fix for navbar */
        .navbar {
            background-color: #65697d !important;
            padding: 0.5rem 0;
        }
        
        /* Temporary debug styles */
        .debug-path {
            position: fixed;
            bottom: 0;
            left: 0;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 5px;
            font-size: 12px;
            z-index: 9999;
            display: none;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="<?php echo SITE_URL; ?>/index.php">
                <img src="<?php echo SITE_URL; ?>/img/logo.png" alt="Logo" height="40">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''); ?>" href="<?php echo SITE_URL; ?>/index.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'courses.php' ? 'active' : ''); ?>" href="<?php echo SITE_URL; ?>/courses.php">Cours</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''); ?>" href="<?php echo SITE_URL; ?>/about.php">À propos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''); ?>" href="<?php echo SITE_URL; ?>/contact.php">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Debug path information (remove in production) -->
    <div class="debug-path">
        SITE_URL: <?php echo SITE_URL; ?><br>
        Current file: <?php echo $_SERVER['PHP_SELF']; ?><br>
        CSS Path: <?php echo SITE_URL; ?>/css/style.css
    </div>