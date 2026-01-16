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
