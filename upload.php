<?php
session_start();
require 'db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
    $imagePath = "uploads/" . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath);

    $stmt = $pdo->prepare("INSERT INTO images (user_id, image_url, title) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION["user_id"], $imagePath, $_POST["title"]]);

    header("Location: dashboard.php");
}
?>

<form method="post" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Image Title" required>
    <input type="file" name="image" required>
    <button type="submit">Upload</button>
</form>
