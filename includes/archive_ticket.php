<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id'])) {
    $ticketId = intval($_GET['id']);

    // archive the ticket
    $stmt = $pdo->prepare("UPDATE tickets SET is_archived = 1 WHERE id = ?;");
    $stmt->execute([$ticketId]);

    header("Location: ../dashboard.php");
    exit;
} else {
    echo "No ticket ID provided.";
}

?>