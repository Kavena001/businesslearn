<?php
// Use credentials from Hostinger's MySQL Databases section
$db = new mysqli('localhost', 'u189409396_Kavena2025', 'Adminphoenix25', 'u189409396_formation_prof');

if ($db->connect_error) {
    die("DB FAIL: " . $db->connect_error);
} else {
    echo "DB SUCCESS!";
    $db->close();
}