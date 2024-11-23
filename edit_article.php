<?php
require('connect.php');
session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'editor')) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $error = "Invalid or missing article ID.";
} else {
    $article_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    try {
        $query = "SELECT * FROM articles WHERE article_id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $article_id, PDO::PARAM_INT);
        $statement->execute();
        $article = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$article) {
            $error = "Article not found.";
        }
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

if ($_POST && isset($_POST['title'], $_POST['content'])) {
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (isset($_POST['delete_image']) && $_POST['delete_image'] === 'yes' && $article['image']) {
        $imagePath = 'uploads/' . $article['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
        $article['image'] = null;
    }

    $imageFileName = $article['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($_FILES['image']['tmp_name']);

        if (in_array($fileType, $allowedTypes)) {
            $uploadDir = 'uploads/';
            $imageFileName = uniqid() . '-' . basename($_FILES['image']['name']);
            $targetFile = $uploadDir . $imageFileName;

            if (resizeImage($_FILES['image']['tmp_name'], $targetFile, 800, 800)) {
                if ($article['image'] && file_exists('uploads/' . $article['image'])) {
                    unlink('uploads/' . $article['image']);
                }
            } else {
                $error = "Failed to process the new image.";
            }
        } else {
            $error = "Invalid image type. Only JPG, PNG, and GIF are allowed.";
        }
    }

    try {
        $updateQuery = "UPDATE articles SET title = :title, content = :content, image = :image WHERE article_id = :id";
        $updateStmt = $db->prepare($updateQuery);
        $updateStmt->bindValue(':title', $title);
        $updateStmt->bindValue(':content', $content);
        $updateStmt->bindValue(':image', $imageFileName);
        $updateStmt->bindValue(':id', $article_id, PDO::PARAM_INT);

        if ($updateStmt->execute()) {
            header("Location: view_article.php?id=" . $article_id);
            exit;
        } else {
            $error = "Failed to update the article.";
        }
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

function resizeImage($source, $destination, $maxWidth, $maxHeight) {
    $info = getimagesize($source);
    if (!$info) {
        return false;
    }

    list($width, $height) = $info;
    $aspectRatio = $width / $height;

    if ($width > $maxWidth || $height > $maxHeight) {
        if ($width > $height) {
            $newWidth = $maxWidth;
            $newHeight = $maxWidth / $aspectRatio;
        } else {
            $newHeight = $maxHeight;
            $newWidth = $maxHeight * $aspectRatio;
        }
    } else {
        $newWidth = $width;
        $newHeight = $height;
    }

    $image = null;
    switch ($info['mime']) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($source);
            break;
        case 'image/png':
            $image = imagecreatefrompng($source);
            break;
        case 'image/gif':
            $image = imagecreatefromgif($source);
            break;
        default
