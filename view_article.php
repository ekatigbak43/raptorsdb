<?php
require('connect.php');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $article_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    try {
        $query = "SELECT a.title, a.content, a.created_at, u.username AS author 
                  FROM articles a 
                  JOIN users u ON a.author_id = u.user_id 
                  WHERE a.article_id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $article_id, PDO::PARAM_INT);
        $statement->execute();

        $article = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$article) {
            echo "Article not found.";
            exit;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title><?= htmlspecialchars($article['title']) ?></title>
</head>
<body>
    <?php include('nav.php'); ?>
    
    <h1><?= htmlspecialchars($article['title']) ?></h1>
    <p>By: <?= htmlspecialchars($article['author']) ?></p>
    <p>Published on: <?= htmlspecialchars($article['created_at']) ?></p>
    <div>
        <?= nl2br(htmlspecialchars($article['content'])) ?>
    </div>
    <a href="index.php">Back to Articles</a>
    <?php include('footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>    
</body>
</html>
