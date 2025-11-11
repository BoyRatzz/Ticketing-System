<?php
require 'functions.php';
require_login();
require 'db.php';

$title = $_POST['title'];
$description = $_POST['description'];
$recipient_type = $_POST['recipient_type'];
$assigned_to = $_POST['assigned_to'] ?: NULL;
$created_by = $_SESSION['user_id'];

$stmt = $pdo->prepare("INSERT INTO tickets (title, description, recipient_type, assigned_to, created_by) VALUES (?,?,?,?,?);");
$stmt->execute([$title, $description, $recipient_type, $assigned_to, $created_by]);

header("Location: ../dashboard.php");
exit;
?>