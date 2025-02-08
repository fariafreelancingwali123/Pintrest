<?php
$host = 'localhost';
$dbname = 'dbeado9vs9lwft';
$username = 'unvpg3ygrct5a';
$password = 'gnpfxouwrlgy';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
