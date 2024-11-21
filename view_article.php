<?php
require('connect.php');
session_start();

if ($_GET && isset($_GET['id']) && is_numeric($_GET['id'])) {
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
    } catch (PDOException $exception) {
        $error = "Error: " . $exception->getMessage();
    }

    try {
        $comment_query = "SELECT c.comment, c.created_at, u.username 
                          FROM comments c
                          JOIN users u ON c.user_id = u.user_id
                          WHERE c.article_id = :article_id
                          ORDER BY c.created_at DESC";
        $comment_statement = $db->prepare($comment_query);
        $comment_statement->bindValue(':article_id', $article_id, PDO::PARAM_INT);
        $comment_statement->execute();
        $comments = $comment_statement->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $exception) {
        $error = "Error fetching comments: " . $exception->getMessage();
    }
} else {
    $error = "Invalid or missing article ID.";
}

if ($_POST && isset($_POST['comment'])) {
    if (!isset($_SESSION['user_id'])) {
        $error = "You must be logged in to comment.";
    } else {
        $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $user_id = $_SESSION['user_id'];

        try {
            $insert_comment = "INSERT INTO comments (article_id, user_id, comment) VALUES (:article_id, :user_id, :comment)";
            $insert_statement = $db->prepare($insert_comment);
            $insert_statement->bindValue(':article_id', $article_id, PDO::PARAM_INT);
            $insert_statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $insert_statement->bindValue(':comment', $comment);

            if ($insert_statement->execute()) {
                header("Location: view_article.php?id=" . $article_id);
                exit;
            } else {
                $error = "Failed to post comment.";
            }
        } catch (PDOException $exception) {
            $error = "Error posting comment: " . $exception->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include('nav.php'); ?>

    <div class="container my-5">
        <h1><?= htmlspecialchars($article['title']) ?></h1>
        <p><strong>By:</strong> <?= htmlspecialchars($article['author_id']) ?></p>
        <p><?= nl2br(htmlspecialchars($article['content'])) ?></p>
        <hr>

        <h2>Comments</h2>
        <?php if ($comments): ?>
            <ul class="list-group mb-4">
                <?php foreach ($comments as $comment): ?>
                    <li class="list-group-item">
                        <p><?= htmlspecialchars($comment['comment']) ?></p>
                        <small class="text-muted">By <?= htmlspecialchars($comment['username']) ?> on <?= htmlspecialchars($comment['created_at']) ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No comments yet. Be the first to comment!</p>
        <?php endif; ?>

        <?php if (isset($_SESSION['user_id'])): ?>
            <form action="view_article.php?id=<?= $article_id ?>" method="post">
                <div class="mb-3">
                    <textarea name="comment" class="form-control" rows="3" placeholder="Add your comment..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Post Comment</button>
            </form>
        <?php else: ?>
            <p><a href="login.php">Log in</a> to post a comment.</p>
        <?php endif; ?>
    </div>

    <?php include('footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
