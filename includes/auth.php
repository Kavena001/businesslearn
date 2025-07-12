<?php
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function authenticate($username, $password) {
    global $db;
    
    $user = $db->getRow("SELECT * FROM users WHERE username = ?", [$username]);
    
    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }
    
    return false;
}

function logout() {
    session_unset();
    session_destroy();
}
?>