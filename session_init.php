<?php
// Set session lifetime to 8 hours (28800 seconds)
$session_lifetime = 8 * 60 * 60; // 28800 seconds

// Ensure the garbage collector doesn't delete the session file
ini_set('session.gc_maxlifetime', $session_lifetime);

// Ensure the browser cookie doesn't expire when the browser is closed
ini_set('session.cookie_lifetime', $session_lifetime);

// Optional: Force the session cookie to be more secure if on HTTPS
// session_set_cookie_params($session_lifetime);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Absolute Session Expiry (8 hours)
if (isset($_SESSION['user_id'])) {
    if (!isset($_SESSION['created_at'])) {
        $_SESSION['created_at'] = time(); // Fallback for existing sessions
    }

    $now = time();
    $elapsed = $now - $_SESSION['created_at'];

    if ($elapsed > $session_lifetime) {
        // Session expired
        session_unset();
        session_destroy();

        // Clear cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        // Redirect if not already on login page
        $current_page = basename($_SERVER['PHP_SELF']);
        if ($current_page !== 'index.php' && $current_page !== 'login.php') {
            header("Location: index.php?error=Session expired. Please login again.");
            exit();
        }
    }
}
