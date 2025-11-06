<?php 
// session_start() method is required if $_SESSION global variable will be used
session_start();
// imports the database connection from db.php file to the current file
require 'db.php';

// makes sure that the user really clicks the login button preventing manual url navigation
if (isset($_POST['login'])) {
    // passing of username & password inputs from the previous login form
    // $_POST method - for sending data to be processed; hidden from the url
    // $_GET method - for retrieving or filtering data; shown directly in the url afer '?'
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // prepares a secure query to avoid SQL injection
    // '?' acts as a placeholder for the real value to be passed later on
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    // subtitutes the username value into the query, then executes the query itself
    $stmt->execute([$username]);
    // gets or fetches the exact row that matches the query
    // users table is an associative array
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // verifies if the user exists
    // verifies if the password matches that of the specific user password
    if ($user && password_verify($password, $user['password'])) {
        // stores session data for the logged-in user
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['username'];
        // navigates to the dashboard page after logging in successfully
        header("Location: ../dashboard.php");
        // exits the script
        exit;
    } else {
        // sets an error message into the $_SESSION global variable to be displayed into the login page
        $_SESSION['error'] = "Invalid username or password.";
        header("Location: ../login.php");
        exit;
    }
}
?>