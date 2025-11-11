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
        <div class="sidebar-header">
            <h1>Ticketing System</h1>
            <br>
            <h3>Welcome back, <?php echo htmlspecialchars($_SESSION['username']) ?>!</h3>
        </div>
        <div class="sidebar-content">
            <div class="sidebar-content-createaccounts"><h2>Create Accounts</h2></div>
            <div class="sidebar-content-tickets"><h2>Tickets</h2></div>
        </div>
    </div>
    <div class="subcontainer">
        <nav class="navbar">
            <p></p>
            <img src="assets/autobot-logo.png" alt="logo" width="40px" style="margin-right: -50px;">
            <p style="margin-right: 20px; cursor: pointer;">Logout</p>
        </nav>
        <div class="ticket-container">
            <div class="ticket-container-header">
                <h1><?php echo htmlspecialchars($_SESSION['username']) ?> | User</h1>
            </div>

            <div class="ticket-container-subheader">
                <h3>Your Tickets:</h3>
                <span style="display: flex; margin-right: 20px; gap:10px">
                    <h3>Search</h3>
                    <input type="text" style="border-radius: 10px">
                </span>
            </div>

            <div class="btn-ticket-div">
                <button class="btn-ticket">Add Ticket</button>
            </div>

            <div class="ticket-div">
                <table class="tickets-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Recipient</th>
                            <th>Status</th>
                            <th>Creator</th>
                            <th>Created_at</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tickets as $t): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($t['id']) ?></td>
                            <td><?php echo htmlspecialchars($t['title']) ?></td>
                            <td><?php echo nl2br(htmlspecialchars(substr($t['description'], 0, 200))); ?></td>
                            <td>
                                <?php 
                                    if ($t['recipient_type'] === 'general') {
                                        echo 'General (anyone)';
                                    } else {
                                        echo $t('assigned_to') ? htmlspecialchars($t['assigned_to']) : 'Unknown (unassigned)';
                                    }
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($t['status']) ?></td>
                            <td><?php echo htmlspecialchars($t['created_by']) ?? 'Unknown' ?></td>
                            <td><?php echo htmlspecialchars($t['created_at']) ?></td>
                            <td>Edit | Archive</td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Ticket Modal -->
    <div class="modal">
        <div class="modal-content">
            <span class="close-button">$times;</span>
            <h2>Add Ticket</h2>
            <form action="">
                <label for="">Title:</label>
                <input type="text">
                <label for="">Description:</label>
                <input type="text">
                <label for="">Recipient:</label>
                <select name="" id="">
                    <option value="">General (anyone)</option>
                    <option value="">Specific (assign to user)</option>
                </select>
                <label for="">Assign to User:</label>
                <input type="text" name="" id="">
                <button>Add Ticket</button>
            </form>
        </div>
    </div>
</body>
</html>