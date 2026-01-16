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
            $_SESSION['role'] = $user['role']; // Store role in session
            header("Location: dashboard.php");
            exit();
        } else {
            // Login Failed
            header("Location: index.php?error=Invalid username or password");
            exit();
        }
    } catch (PDOException $e) {
        header("Location: index.php?error=System error, please try again later");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}