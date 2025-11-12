<?php
require 'db.php';
require 'functions.php';
require_login();

$title = $_POST['title'];
$description = $_POST['description'];
$recipient = $_POST['recipient_type'];
$createdBy = $_SESSION['user_id'];

// Determine recipient type
if ($recipient === 'general') {
    $recipientType = 'general';
    $assignedTo = null;
} else {
    $recipientType = 'specific';
    $assignedTo = (int)$recipient;
}

$sql = "INSERT INTO tickets (title, description, recipient_type, assigned_to, created_by, status, created_at)
        VALUES (:title, :description, :recipient_type, :assigned_to, :created_by, 'Open', NOW())";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'title' => $title,
    'description' => $description,
    'recipient_type' => $recipientType,
    'assigned_to' => $assignedTo,
    'created_by' => $createdBy
]);

header("Location: ../dashboard.php");
exit;

?>