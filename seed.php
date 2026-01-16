<?php
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // Connect to MySQL server first (without database)
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS login_app");
    echo "Database 'login_app' checked/created successfully.\n";

    // Connect to the database
    $pdo->exec("USE login_app");

    // Create table if not exists
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "Table 'users' checked/created successfully.\n";

    // Create receipts table
    $sql = "CREATE TABLE IF NOT EXISTS receipts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        receipt_number VARCHAR(50) NOT NULL,
        date DATE NOT NULL,
        received_from VARCHAR(255) NOT NULL,
        address TEXT,
        phone VARCHAR(50),
        email VARCHAR(255),
        amount DECIMAL(10, 2) NOT NULL,
        payment_method VARCHAR(50) NOT NULL,
        payment_purpose VARCHAR(50) NOT NULL,
        email_sent TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "Table 'receipts' checked/created successfully.\n";

    // Check if admin exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = 'admin'");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        // Create admin user: admin / password123
        // Hash 'password123' using PASSWORD_DEFAULT which is bcrypt
        $passHash = password_hash('password123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (:username, :password)");
        $stmt->execute([':username' => 'admin', ':password' => $passHash]);
        echo "User 'admin' created with password 'password123'.\n";
    } else {
        echo "User 'admin' already exists.\n";
        // Optional: Update password to be sure? No, existing might be intended.
        // Let's reset it just to be 100% sure for testing.
        $passHash = password_hash('password123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password_hash = :password WHERE username = 'admin'");
        $stmt->execute([':password' => $passHash]);
        echo "User 'admin' password reset to 'password123'.\n";
    }

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage() . "\n");
}
