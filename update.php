<?php
require('connect.php');
session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'editor')) {
    header("Location: login.php");
    exit;
}

if ($_POST && isset($_POST['title'], $_POST['content'], $_POST['author_id'], $_POST['id'])) {
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $author_id = filter_input(INPUT_POST, 'author_id', FILTER_SANITIZE_NUMBER_INT);
    $article_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

    $query = "UPDATE articles 
              SET title = :title, content = :content, author_id = :author_id, created_at = NOW()
              WHERE article_id = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':title', $title);
    $statement->bindValue(':content', $content);
    $statement->bindValue(':author_id', $author_id, PDO::PARAM_INT);
    $statement->bindValue(':id', $article_id, PDO::PARAM_INT);

    if ($statement->execute()) {
        header("Location: update.php?id={$article_id}");
        exit;
    } else {
        echo "Error updating article.";
    }
} else if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $article_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $query = "SELECT * FROM articles WHERE article_id = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $article_id, PDO::PARAM_INT);
    $statement->execute();
    $article = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$article) {
        echo "Article not found.";
        exit;
    }
} else {
    echo "Invalid or missing article ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Article</title>
</head>
<body>
    <h1>Update Article</h1>

    <?php if ($article): ?>
        <form method="post">
            <input type="hidden" name="id" value="<?= htmlspecialchars($article['article_id']) ?>">

            <label for="title">Title:</label>
            <inp
