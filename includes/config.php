<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'u189409396_formation_prof');
define('DB_USER', 'u189409396_Kavena2025');
define('DB_PASS', 'Adminphoenix25');

// Site settings
define('SITE_URL', 'http://localhost/formation-pro');
define('SITE_TITLE', 'Formation Professionnelle');

// File upload paths
define('UPLOAD_PATH', dirname(__DIR__) . '/uploads/');
define('COURSE_IMAGE_PATH', UPLOAD_PATH . 'courses/');
define('TESTIMONIAL_IMAGE_PATH', UPLOAD_PATH . 'testimonials/');

// Start session
session_start();

// Error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>