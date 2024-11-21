<?php
require('connect.php');
session_start();

$search_results = [];
if ($_GET && isset($_GET['q'])) {
    $query = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    try {
        $sql = "SELECT article_id, title, created_at 
                FROM articles 
                WHERE title LIKE :query OR content LIKE :query 
                ORDER BY created_at DESC";
        $statement = $db->prepare($sql);
        $statement->bindValue(':query', '%' . $query . '%');
        $statement->execute();
        $search_results = $statement->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $exception) {
        $error = "Error: " . $exception->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include('nav.php'); ?>
    <div class="container my-5">
        <h1 class="text-center">Search Results</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php elseif (count($search_results) > 0): ?>
            <ul class="list-group mt-4">
                <?php foreach ($search_results as $result): ?>
                    <li class="list-group-item">
                        <a href="view_article.php?id=<?= $result['article_id'] ?>">
                            <?= htmlspecialchars($result['title']) ?>
                        </a>
                        <small class="text-muted">Published on <?= htmlspecialchars($result['created_at']) ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-center mt-4">No results found for your search query.</p>
        <?php endif; ?>
    </div>
    <?php include('footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
