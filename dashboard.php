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
            WHERE t.is_archived = 0
            ORDER BY t.created_at DESC;";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
} else {
    $sql = "SELECT t.*, u_creator.username AS creator_name, u_assignee.username AS assigned_name
            FROM tickets t
            LEFT JOIN users u_creator ON t.created_by = u_creator.id
            LEFT JOIN users u_assignee ON t.assigned_to = u_assignee.id
            WHERE (t.created_by = :me AND t.is_archived = 0)
                OR (t.recipient_type = 'general' AND t.is_archived = 0)
                OR (t.recipient_type = 'specific' AND t.assigned_to = :me AND t.is_archived = 0)
            ORDER BY t.created_at DESC;";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['me' => $currentUserId]);
}

$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// fetch all users
$userQuery = $pdo->prepare("SELECT * FROM users ORDER BY username ASC");
$userQuery->execute();
$users = $userQuery->fetchAll(PDO::FETCH_ASSOC);

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
    <!-- Show Error or Success -->
    <?php
    if (isset($_SESSION['error'])) {
        echo "<script>alert('". addslashes($_SESSION['error']) ."')</script>";
        unset($_SESSION['error']);
    }

    if (isset($_SESSION['success'])) {
    echo "<script>alert('" . addslashes($_SESSION['success']) . "');</script>";
    unset($_SESSION['success']);
}
    ?>

    <div class="sidebar">
        <div class="sidebar-header">
            <h1>Ticketing System</h1>
            <br>
            <h3>Welcome back, <?php echo htmlspecialchars($_SESSION['username']) ?>!</h3>
        </div>
        <div class="sidebar-content">
            <?php if ($_SESSION['role'] === 'superuser'): ?>
            <div class="sidebar-content-createaccounts"><h2>Create Accounts</h2></div>
            <?php endif; ?>
            <div class="sidebar-content-tickets"><h2>Tickets</h2></div>
        </div>
    </div>
    <div class="subcontainer">
        <nav class="navbar">
            <p></p>
            <img src="assets/autobot-logo.png" alt="logo" width="40px" style="margin-right: -50px;">
            <p style="margin-right: 20px; cursor: pointer;">Logout</p>
        </nav>

        <!-- Ticket Container (shown by default) -->
        <div class="ticket-container">
            <div class="ticket-container-header">
                <h1><?php echo htmlspecialchars($_SESSION['username']) ?> | User</h1>
            </div>

            <div class="ticket-container-subheader">
                <h3>Your Tickets:</h3>
                <span style="display: flex; margin-right: 20px; gap:10px;">
                    <h3>Search</h3>
                    <input type="text" style="border-radius: 10px; text-indent: 10px;" id="searchTicket">
                </span>
            </div>

            <div class="btn-ticket-div">
                <button class="btn-ticket">Add Ticket</button>
            </div>

            <div class="ticket-div">
                <table class="tickets-table">
                    <thead>
                        <tr>
                            <th>Ticket #</th>
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
                                        echo $t['assigned_name'] ? htmlspecialchars($t['assigned_name']) : 'Unknown (unassigned)';
                                    }
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($t['status']) ?></td>
                            <td><?php echo htmlspecialchars($t['creator_name']) ?? 'Unknown' ?></td>
                            <td><?php echo htmlspecialchars($t['created_at']) ?></td>
                            <td style="display: flex; gap: 5px;">
                                <?php if ($_SESSION['role'] === 'superuser' || $t['created_by'] == $_SESSION['user_id']): ?>
                                    <a href="#" class="edit-btn"
                                        data-id="<?php echo $t['id']; ?>"
                                        data-title="<?php echo htmlspecialchars($t['title']); ?>"
                                        data-description="<?php echo htmlspecialchars($t['description']); ?>"
                                        data-recipient-type="<?php echo $t['recipient_type']; ?>"
                                        data-assigned-to="<?php echo $t['assigned_to']; ?>"
                                        data-status="<?php echo $t['status']; ?>">
                                        Edit
                                    </a>

                                    <p>|</p>

                                    <a href="includes/archive_ticket.php?id=<?php echo $t['id'] ?>" onclick="return confirm('Are you sure you want to archive this ticket?');">
                                        Archive
                                    </a>
                                <?php else: ?>
                                    <span style="color: gray;">No actions</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Account Container (hidden by default) -->
         <div id="createAccountModal" class="account-container">
            <div class="ticket-container-header">
                <h1><?php echo htmlspecialchars($_SESSION['username']) ?> | <?php echo htmlspecialchars($_SESSION['role']) ?></h1>
            </div>

            <div class="add-account-container">
                <h3>Create Account:</h3>
                <form action="includes/create_account.php" method="POST" class="add-account-form">
                    <label for="username" style="margin-right: -12px;">Username</label>
                    <input type="text" name="username" id="username" required>

                    <label for="password_hash" style="margin-right: -12px;">Password</label>
                    <input type="password" name="password_hash" id="password_hash" required>

                    <label for="confirm_password_hash" style="margin-right: -12px;">Confirm Password</label>
                    <input type="password" name="confirm_password_hash" id="confirm_password_hash" required>

                    <label for="role" style="margin-right: -12px;">Role</label>
                    <select name="role" id="role" style="font-size: 1rem">
                        <option value="superuser">Super User</option>
                        <option value="user">Regular User</option>
                    </select>

                    <button style="padding-right: 10px; padding-left:10px; padding-top: 2px; padding-bottom: 2px; font-size: 1rem">Add Account</button>
                </form>
            </div>

            <div class="ticket-container-subheader">
                <h3>List of Accounts:</h3>
                <span style="display: flex; margin-right: 20px; gap:10px;">
                    <h3>Search</h3>
                    <input type="text" style="border-radius: 10px; text-indent: 10px;" id="searchAccount">
                </span>
            </div>

            <div class="ticket-div">
                <table class="accounts-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Username</th>
                            <th>Password_hash</th>
                            <th>Role</th>
                            <th>Created_at</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?php echo($u['id']) ?></td>
                            <td><?php echo htmlspecialchars($u['username']) ?></td>
                            <td><?php echo htmlspecialchars($u['password_hash']) ?></td>
                            <td><?php echo htmlspecialchars($u['role']) ?></td>
                            <td><?php echo htmlspecialchars($u['created_at']) ?></td>
                            <td style="display: flex; gap: 5px;">
                                <a href="#" class="edit-account"
                                    data-id="<?php echo $u['id']; ?>"
                                    data-title="<?php echo htmlspecialchars($u['username']); ?>"
                                    data-description="<?php echo htmlspecialchars($u['password_hash']); ?>">
                                    Edit
                                </a>

                                <p>|</p>

                                <a href="includes/delete_account.php?id=<?php echo $u['id'] ?>" onclick="return confirm('Are you sure you want to delete this account?');">
                                    Delete
                                </a>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
         </div>
    </div>

    <!-- Edit Account Modal -->
    <div id="editAccountModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close-edit-modal" style="float: right; cursor: pointer; font-size: 1.5rem;">&times;</span>
            <h2>Edit Account</h2>
            <form action="includes/edit_account.php" method="POST" class="edit-account-form">
                <input type="hidden" name="id" id="edit-id">

                <label for="edit-username">Username</label>
                <input type="text" name="username" id="edit-username" required>

                <label for="edit-password">New Password (leave blank to keep current)</label>
                <input type="password" name="password_hash" id="edit-password">

                <label for="edit-role">Role</label>
                <select name="role" id="edit-role" style="font-size: 1rem">
                    <option value="superuser">Super User</option>
                    <option value="user">Regular User</option>
                </select>

                <div style="margin-top: 15px;">
                    <button type="submit" style="padding: 6px 12px; font-size: 1rem;">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    <script src="account.js"></script>

    <!-- Add Ticket Modal -->
    <div id="addTicketModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2>Add Ticket</h2>
            <br>
            <form id="addTicketForm" action="includes/add_ticket.php" method="POST" class="addTicketForm">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" required>

                <label for="description">Description:</label>
                <textarea name="description" id="description" required></textarea>

                <label for="recipient_type">Recipient:</label>
                <select name="recipient_type" id="recipient_type">
                    <option value="general">General (anyone)</option>

                    <optgroup label="Specific User">
                    <?php foreach ($users as $u): ?>
                        <option value="<?php echo htmlspecialchars($u['id']); ?>">
                        <?php echo htmlspecialchars($u['username']); ?>
                        </option>
                    <?php endforeach; ?>
                    </optgroup>
                </select>

                <button>Add Ticket</button>
            </form>
        </div>
    </div>

    <!-- Edit Ticket Modal -->
    <div id="editTicketModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2>Edit Ticket</h2>
            <form id="editTicketForm" action="includes/edit_ticket.php" method="POST" class="editTicketForm">
                <input type="hidden" name="ticket_id" id="editTicketId">

                <label for="editTitle">Title:</label>
                <input type="text" name="title" id="editTitle" required>

                <label for="editDescription">Description:</label>
                <textarea name="description" id="editDescription" required></textarea>

                <label for="editRecipientType">Recipient Type:</label>
                <select name="recipient_type" id="editRecipientType" required>
                    <option value="general">General</option>
                    <option value="specific">Specific</option>
                </select>

                <label for="editAssignedTo" id="editAssignedToLabel" style="display: none;">Assign To:</label>
                <select name="assigned_to" id="editAssignedTo" style="display: none;">
                    <option value="">-- None --</option>
                    <?php
                        $userStmt = $pdo->query("SELECT id, username FROM users ORDER BY username ASC");
                        while ($user = $userStmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='{$user['id']}'>{$user['username']}</option>";
                        }
                    ?>
                </select>

                <label for="editStatus">Status:</label>
                <select name="status" id="editStatus">
                    <option value="open">Open</option>
                    <option value="done">Done</option>
                </select>

                <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>
</body>
<script src="ticket.js"></script>
</html>