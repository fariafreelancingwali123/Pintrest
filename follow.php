<?php
session_start();
require 'db.php';

if (!isset($_SESSION["user_id"]) || !isset($_GET["follow_id"])) {
    header("Location: dashboard.php");
    exit;
}

$stmt = $pdo->prepare("INSERT INTO follows (follower_id, following_id) VALUES (?, ?)");
$stmt->execute([$_SESSION["user_id"], $_GET["follow_id"]]);

header("Location: profile.php?user=" . $_GET["follow_id"]);
?>
