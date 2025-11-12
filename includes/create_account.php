<?php
require 'db.php';
require 'functions.php';
session_start();

if (!is_superuser()) {
    $_SESSION['error'] = "Access denied";
    header("Location: ../dashboard.php");
    exit;
}

if (isset($_POST['username']) && isset($_POST['password_hash']) && isset($_POST['role'])) {
    if ($_POST['password_hash'] === $_POST['confirm_password_hash']) {
        $username = trim($_POST['username']);
        $password = $_POST['password_hash'];
        $role = $_POST['role'];

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, role) VALUES (?,?,?);");
            $stmt->execute([$username, $password_hash, $role]);
            $_SESSION['success'] = "Account created successfully.";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error creating account: " . $e->getMessage();
        }

        header("Location: ../dashboard.php");
        exit;
    } else {
        $_SESSION['error'] = "Passwords do not match";
        header("Location: ../dashboard.php");
        exit;
    }
}
?>