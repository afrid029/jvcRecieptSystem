<?php
require_once 'session_init.php';
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        header("Location: index.php?error=All fields are required");
        exit();
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            // Login Success
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['country_id'] = $user['country_id']; // Store country_id
            $_SESSION['created_at'] = time(); // Track login time for expiry

            // Check if request is AJAX/XHR
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'redirect' => 'dashboard.php']);
                exit();
            }

            header("Location: dashboard.php");
            exit();
        } else {
            // Login Failed
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Type: application/json');
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
                exit();
            }

            header("Location: index.php?error=Invalid username or password");
            exit();
        }
    } catch (PDOException $e) {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'System error']);
            exit();
        }
        header("Location: index.php?error=System error, please try again later");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}