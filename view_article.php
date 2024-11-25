<?php
require('connect.php');
session_start();

if ($_GET && isset($_GET['id']) && is_numeric($_GET['id'])) {
    $article_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    $article_query = "SELECT a.title, a.content, a.created_at, a.image, u.username AS author 
                      FROM articles a 
                      JOIN users u ON a.author_id = u.user_id 
                      WHERE a.article_id = :id";
    $article_statement = $db->prepare($article_query);
    $article_statement->bindValue(':id', $article_id, PDO::PARAM_INT);
    $article_statement->execute();
    $article = $article_statement->fetch(PDO::FETCH_ASSOC);

    if (!$article) {
        $error = "Article not found.";
    }

    $comment_query = "SELECT c.comment_id, c.comment, c.created_at, u.username 
                      FROM comments c
                      JOIN users u ON c.user_id = u.user_id
                      WHERE c.article_id = :article_id
                      ORDER BY c.created_at DESC";
    $comment_statement = $db->prepare($comment_query);
    $comment_statement->bindValue(':article_id', $article_id, PDO::PARAM_INT);
    $comment_statement->execute();
    $comments = $comment_statement->fetchAll(PDO::FETCH_ASSOC);
} else {
    $error = "Invalid or missing article ID.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title><?= htmlspecialchars($article['title'] ?? 'Article') ?></title>
</head>
<body>
    <?php include('nav.php'); ?>

    <div class="container my-5">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php else: ?>
            <?php if (!empty($article['image'])): ?>
                <div class="mb-4 text-center">
                    <img src="uploads/<?= htmlspecialchars($article['image']) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($article['title']) ?>">
                </div>
            <?php endif; ?>

            <h1 class="text-center"><?= htmlspecialchars($article['title']) ?></h1>
            <p><strong>By:</strong> <?= htmlspecialchars($article['author']) ?></p>
            <p><strong>Published on:</strong> <?= htmlspecialchars($article['created_at']) ?></p>
            <div class="mb-5"><?= nl2br(htmlspecialchars($article['content'])) ?></div>

            <?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'editor')): ?>
                <div class="text-end">
                    <a href="edit_article.php?id=<?= $article_id ?>" class="btn btn-warning">Edit Article</a>
                </div>
            <?php endif; ?>

            <h2>Comments</h2>
            <?php if (!empty($comments)): ?>
                <ul class="list-group">
                    <?php foreach ($comments as $comment): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1"><?= htmlspecialchars($comment['comment']) ?></p>
                                <small><strong>By:</strong> <?= htmlspecialchars($comment['username']) ?> | <strong>On:</strong> <?= htmlspecialchars($comment['created_at']) ?></small>
                            </div>
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <a href="view_article.php?id=<?= $article_id ?>&delete_comment=<?= $comment['comment_id'] ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Are you sure you want to delete this comment?');">
                                    Delete
                                </a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No comments yet. Be the first to comment!</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <?php include('footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
