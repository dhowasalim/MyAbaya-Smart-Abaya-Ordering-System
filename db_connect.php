<?php


$connectionString = "mysql:host=localhost;dbname=myabaya_db;charset=utf8mb4";
$user = "root";
$pass = "";

try {
    $pdo = new PDO($connectionString, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
