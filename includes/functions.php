<?php 
session_start();

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: /TicketingWebsite/login.php');
        exit;
    }
}

function issuper_user() {
    return (isset($_SESSION['user_id']) && $_SESSION['role'] == 'superuser' && isset($_SESSION['role']));
}
?>