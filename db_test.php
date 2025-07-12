<?php
// Use credentials from Hostinger's MySQL Databases section
$db = new mysqli('localhost', 'u1234_yourdbuser', 'YourActualPassword', 'u1234_yourdbname');

if ($db->connect_error) {
    die("DB FAIL: " . $db->connect_error);
} else {
    echo "DB SUCCESS!";
    $db->close();
}