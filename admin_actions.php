<?php
require_once 'session_init.php';
require 'db.php';

// Authentication and Authorization Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
    header("Location: dashboard.php?error=Access Denied");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        if (empty($username) || empty($password)) {
            header("Location: admin_management.php?error=Username and Password are required");
            exit();
        }

        // Check if username exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            header("Location: admin_management.php?error=Username already exists");
            exit();
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, role) VALUES (?, ?, 'admin')");
        $stmt->execute([$username, $password_hash]);

        header("Location: admin_management.php?msg=Admin created successfully");
        exit();

    } elseif ($action === 'delete') {
        $id = $_POST['id'];

        // Prevent deleting self
        if ($id == $_SESSION['user_id']) {
            header("Location: admin_management.php?error=You cannot delete yourself");
            exit();
        }

        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);

        header("Location: admin_management.php?msg=Admin deleted successfully");
        exit();

    } elseif ($action === 'update_password') {
        $id = $_POST['id'];
        $password = $_POST['password'];

        if (empty($password)) {
            header("Location: admin_management.php?error=Password required");
            exit();
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        $stmt->execute([$password_hash, $id]);

        header("Location: admin_management.php?msg=Password updated successfully");
        exit();
    }
}
header("Location: admin_management.php");
exit();
?>