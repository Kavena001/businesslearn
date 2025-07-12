<?php
echo "<h1>Basic PHP Test</h1>";
echo "<p>If you see this, PHP works.</p>";

// Test includes/config.php
require 'includes/config.php';
echo "<p>Config loaded successfully.</p>";

// Test database connection
try {
    $db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    echo "<p>Database connected!</p>";
} catch (PDOException $e) {
    die("<p>DB Error: " . $e->getMessage() . "</p>");
}