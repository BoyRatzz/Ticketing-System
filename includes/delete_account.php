<?php 
require 'db.php';
require 'functions.php';
session_start();

if (!is_superuser()) {
    $_SESSION['error'] = "Access denied!";
    header("Location: ../dashboard.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    if ($id == $_SESSION['user_id']) {
        $_SESSION['error'] = "You cannot delete your own account";
        header("Location: ../dashboard.php");
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?;");
        $stmt->execute([$id]);

        $_SESSION['success'] = "Account deleted successfully!";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error deleting account: " . $e->getMessage();
    }
} else {
    $_SESSION['error'] = "No account ID provided.";
}

header("Location: ../dashboard.php");
exit;

?>