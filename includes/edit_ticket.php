<?php
session_start();
require 'db.php';
require 'functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['ticket_id']);
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $recipient_type = $_POST['recipient_type'];
    $assigned_to = !empty($_POST['assigned_to']) ? intval($_POST['assigned_to']) : null;
    $status = $_POST['status'];

    $stmt = $pdo->prepare("
        UPDATE tickets
        SET title = ?, description = ?, recipient_type = ?, assigned_to = ?, status = ?, updated_at = NOW()
        WHERE id = ?
    ");

    $stmt->execute([$title, $description, $recipient_type, $assigned_to, $status, $id]);

    header("Location: ../dashboard.php");
    exit;
}
?>