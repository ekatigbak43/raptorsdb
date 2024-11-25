<?php
require('connect.php');
session_start();

$articles_per_page = 5;

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

$offset = ($page - 1) * $articles_per_page;

try {
    $total_query = "SELECT COUNT(*) AS total FROM articles";
    $total_statement = $db->prepare($total_query);
    $total_statement->execute();
    $total_articles = $total_statement->fetch(PDO::FETCH_ASSOC)['total'];

    $total_pages = ceil($total_articles / $articles_per_page);

    $query = "SELECT a.article_id, a.title, a.content, a.image, a.created_at, u.username AS author 
              FROM articles a 
              JOIN users u ON a.author_id = u.user_id 
              ORDER BY a.created_at DESC 
              LIMIT :limit OFFSET :offset";
    $statement = $db->prepare($query);
    $statement->bindValue(':limit', $articles_per_page, PDO::PARAM_INT);
    $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
    $statement->execute();

    $articles = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include('nav.php'); ?>

    <div class="container my-5">
        <h1 class="text-center mb-4">Articles</h1>

        <?php if ($articles && count($articles) > 0): ?>
            <div class="row justify-content-center">
                <?php foreach ($articles as $article): ?>
                    <div class="col-md-8 mb-4">
                        <div class="card">
                            <div class="row g-0 align-items-center">
                                <?php if (!empty($article['image'])): ?>
                                    <div class="col-md-4 d-flex justify-content-center align-items-center">
                                        <img src="uploads/<?= htmlspecialchars($article['image']) ?>" 
                                             class="img-fluid rounded-start" 
                                             alt="Article Image" 
                                             style="max-height: 200px; max-width: 100%;">
                                    </div>
                                <?php endif; ?>

                                <div class="<?= empty($article['image']) ? 'col-md-12' : 'col-md-8' ?>">
                                    <div class="card-body">
                                        <h2 class="card-title"><?= htmlspecialchars($article['title']) ?></h2>
                                        <p class="card-text"><strong>By:</strong> <?= htmlspecialchars($article['author']) ?></p>
                                        <p class="card-text"><?= nl2br(htmlspecialchars(substr($article['content'], 0, 200))) ?></p>
                                        <p class="card-text"><small class="text-muted">Published on: <?= htmlspecialchars($article['created_at']) ?></small></p>
                                        <a href="view_article.php?id=<?= $article['article_id'] ?>" class="btn btn-primary">Read Full Article</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center">No articles found.</p>
        <?php endif; ?>

        <div class="pagination d-flex justify-content-center mt-4">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>" class="btn btn-outline-primary mx-1">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>" class="btn <?= $i === $page ? 'btn-primary' : 'btn-outline-primary' ?> mx-1"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?= $page + 1 ?>" class="btn btn-outline-primary mx-1">Next</a>
            <?php endif; ?>
        </div>
    </div>

    <?php include('footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>    
</body>
</html>
