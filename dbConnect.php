<?php

function dbConnect() {
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $charset = 'utf8mb4';
    $targetDb = 'donationcli_db';

    $dsn = "mysql:host=$host;charset=$charset";
    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$targetDb`");
        $pdo->exec("USE `$targetDb`");

        return $pdo;
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        exit;
    }
}
?>
