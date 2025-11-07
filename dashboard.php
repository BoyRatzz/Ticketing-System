<?php
require 'includes/functions.php';
require_login();
require 'includes/db.php';

$currentUserId = $_SESSION['user_id'];
$currentRole = $_SESSION['role'];

if ($currentRole === 'superuser') {
    $sql = "SELECT t.*, u_creator.username AS creator_name, u_assignee.username AS assigned_name
            FROM tickets t
            LEFT JOIN users u_creator ON t.created_by = u_creator.id
            LEFT JOIN users u_assignee ON t.assigned_to = u_assignee.id
            ORDER BY t.created_at DESC;";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
} else {
    $sql = "SELECT t.*, u_creator.username AS creator_name, u_assignee.username AS assigned_name
            FROM tickets t
            LEFT JOIN users u_creator ON t.created_by = u_creator.id
            LEFT JOIN users u_assignee ON t.assigned_to = u_assignee.id
            WHERE (t.created_by = :me)
                OR (t.recipient_type = 'general')
                OR (t.recipient_type = 'specific' AND t.assigned_to = :me)
            ORDER BY t.created_at DESC;";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['me' => $currentUserId]);
}

$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body class="container">
    <div class="sidebar">

    </div>
    <div class="subcontainer">
        <nav class="navbar">

        </nav>
        <div class="ticket-container">
            
        </div>
    </div>
</body>
</html>