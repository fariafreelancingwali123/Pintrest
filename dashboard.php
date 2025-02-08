<?php
session_start();
require 'db.php';
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
$stmt = $pdo->query("SELECT * FROM images");
$images = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            background-color: #fafafa;
            padding: 20px;
            padding-top: 100px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 24px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: white;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        h1 {
            font-size: 20px;
            color: #111;
            font-weight: 600;
        }

        .nav-links {
            display: flex;
            gap: 24px;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: #111;
            padding: 12px 16px;
            border-radius: 24px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.2s ease-in-out;
        }

        .upload-btn {
            background-color: #e60023;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .upload-btn:hover {
            background-color: #ad081b !important;
            transform: scale(1.05);
        }

        .logout-btn {
            color: #5f5f5f !important;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logout-btn:hover {
            background-color: #e9e9e9;
        }

        .image-grid {
            margin: 0 auto;
            max-width: 1800px;
            columns: 5 236px;
            column-gap: 20px;
            padding: 8px;
        }

        .pin-container {
            break-inside: avoid;
            margin-bottom: 20px;
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            background-color: white;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .pin-container:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .pin-container img {
            width: 100%;
            height: auto;
            display: block;
            transition: filter 0.3s;
        }

        .pin-container:hover img {
            filter: brightness(0.9);
        }

        .pin-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(transparent 70%, rgba(0, 0, 0, 0.6));
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 16px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .pin-container:hover .pin-overlay {
            opacity: 1;
        }

        .pin-actions {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 8px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .pin-container:hover .pin-actions {
            opacity: 1;
        }

        .pin-button {
            background-color: #e60023;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 24px;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.2s;
        }

        .pin-button:hover {
            background-color: #ad081b;
        }

        .pin-save {
            background-color: #e60023;
        }

        .pin-menu {
            background-color: rgba(255, 255, 255, 0.9);
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .pin-menu:hover {
            background-color: white;
        }

        .pin-title {
            color: white;
            font-size: 14px;
            font-weight: 600;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
            margin-bottom: 8px;
        }

        .search-bar {
            flex-grow: 1;
            max-width: 800px;
            margin: 0 24px;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 12px 48px;
            border-radius: 24px;
            border: none;
            background-color: #e9e9e9;
            font-size: 16px;
            transition: background-color 0.2s;
        }

        .search-input:focus {
            outline: none;
            background-color: #ddd;
        }

        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #5f5f5f;
        }

        @media (max-width: 1200px) {
            .image-grid {
                columns: 4 236px;
            }
        }

        @media (max-width: 992px) {
            .image-grid {
                columns: 3 236px;
            }
            .search-bar {
                max-width: 400px;
            }
        }

        @media (max-width: 768px) {
            .image-grid {
                columns: 2 236px;
            }
            .header {
                flex-wrap: wrap;
                gap: 16px;
            }
            .search-bar {
                order: 3;
                max-width: 100%;
                margin: 0;
            }
        }

        @media (max-width: 480px) {
            .image-grid {
                columns: 1 236px;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>Pinterest Clone</h1>
        
        <div class="search-bar">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-input" placeholder="Search for anything">
        </div>

        <div class="nav-links">
            <a href="upload.php" class="upload-btn">
                <i class="fas fa-plus"></i>
                Upload
            </a>
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </div>
    </header>

    <div class="image-grid">
        <?php foreach ($images as $image): ?>
            <div class="pin-container">
                <img src="<?= htmlspecialchars($image['image_url']) ?>" alt="<?= htmlspecialchars($image['title']) ?>">
                <div class="pin-overlay">
                    <div class="pin-actions">
                        <button class="pin-button pin-save">Save</button>
                        <div class="pin-menu">
                            <i class="fas fa-ellipsis-h"></i>
                        </div>
                    </div>
                    <p class="pin-title"><?= htmlspecialchars($image['title']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
