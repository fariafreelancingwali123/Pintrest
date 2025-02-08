<?php
session_start();
require 'db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION["user_id"]]);
$user = $stmt->fetch();

$stmt = $pdo->prepare("SELECT * FROM images WHERE user_id = ?");
$stmt->execute([$_SESSION["user_id"]]);
$images = $stmt->fetchAll();
?>

<h1>Welcome, <?= $user["username"] ?></h1>
<a href="dashboard.php">Back to Dashboard</a>

<h2>Your Images</h2>
<div>
    <?php foreach ($images as $image): ?>
        <img src="<?= $image['image_url'] ?>" alt="<?= $image['title'] ?>">
        <p><?= $image['title'] ?></p>
    <?php endforeach; ?>
</div>
