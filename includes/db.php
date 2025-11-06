<?php
$host = 'localhost';
$dbname = 'ticketing_db';
$username = 'root';
$password = '';

try {
    // connecting to the database
    // instantiate PDO instead of mysqli for flexibility
    // syntax: PDO (host; dbname; charset=utf8, username, password)
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}