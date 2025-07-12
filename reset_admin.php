<?php
require 'includes/config.php';
require 'includes/db.php';

// Bypass all authentication to reset admin
$new_password = 'admin_password123'; // Change this!
$hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

$db->update(
    "UPDATE users SET password = ? WHERE username = 'admin'",
    [$hashed_password]
);

echo "Password reset to: $new_password<br>";
echo "Hash stored: $hashed_password<br>";
echo '<a href="/admin/login.php">Login now</a>';

// SECURITY: Immediately delete this file after use!
unlink(__FILE__);
?>