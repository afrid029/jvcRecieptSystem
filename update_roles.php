<?php
require 'db.php';

try {
    // 1. Add role column if it doesn't exist
    // We use a safe way to check column existence or just try to add it
    // ENUM: 'super_admin', 'admin'
    $sql = "ALTER TABLE users ADD COLUMN role ENUM('super_admin', 'admin') DEFAULT 'admin' NOT NULL";
    $pdo->exec($sql);
    echo "Column 'role' added successfully.<br>";
} catch (PDOException $e) {
    echo "Note: Column 'role' might already exist or error: " . $e->getMessage() . "<br>";
}

try {
    // 2. Update the main admin user to super_admin
    // Assuming the main admin username is 'admin'
    $stmt = $pdo->prepare("UPDATE users SET role = 'super_admin' WHERE username = 'admin'");
    $stmt->execute();
    echo "User 'admin' promoted to Super Admin.<br>";
} catch (PDOException $e) {
    echo "Error updating user role: " . $e->getMessage();
}
?>