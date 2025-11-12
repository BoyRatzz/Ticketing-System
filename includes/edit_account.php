<?php 
require 'db.php';
require 'functions.php';
session_start();

if (!is_superuser()) {
    $_SESSION['error'] = "Access denied.";
    header("Location: ../dashboard.php");
    exit;
}

if (isset($_POST['id'], $_POST['username'], $_POST['role'])) {
    $id = intval($_POST['id']);
    $username = trim($_POST['username']);
    $role = $_POST['role'];
    $password = $_POST['password_hash'];

    try {
        if (!empty($password)) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET username = ?, password_hash = ?, role = ? WHERE id = ?");
            $stmt->execute([$username, $password_hash, $role, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET username = ?, role = ? WHERE id = ?");
            $stmt->execute([$username, $role, $id]);
        }
        $_SESSION['success'] = "Account updated successfully.";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error updating account: " . $e->getMessage();
    }

    header("Location: ../dashboard.php");
    exit;
} else {
    $_SESSION['error'] = "Invalid form submission.";
    header("Location: ../dashboard.php");
    exit;
}
?>