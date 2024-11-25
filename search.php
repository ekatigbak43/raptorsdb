<?php
require('connect.php');
session_start();

$search_results = [];
$error = null;

$items_per_page = isset($_GET['n']) && is_numeric($_GET['n']) ? (int)$_GET['n'] : 5;

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

if ($_GET && isset($_GET['q'])) {
    $query = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    try {
        $count_sql = "SELECT COUNT(*) AS total 
                      FROM articles 
                      WHERE title LIKE :query OR content LIKE :query";
        $count_statement = $db->prepare($count_sql);
        $count_statement->bindValue(':query', '%' . $query . '%');
        $count_statement->execute();
        $total_results = $count_statement->fetch(PDO::FETCH_ASSOC)['total'];

        $total_pages = ceil($total_results / $items_per_page);

        $sql = "SELECT article_id, title, created_at 
                FROM articles 
                WHERE title LIKE :query OR content LIKE :query 
                ORDER BY created_at DESC 
                LIMIT :limit OFFSET :offset";
        $statement = $db->prepare($sql);
        $statement->bindValue(':query', '%' . $query . '%');
        $statement->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
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
            <nav aria-label="Search result pages" class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?q=<?= urlencode($query) ?>&n=<?= $items_per_page ?>&page=<?= $page - 1 ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo; Previous</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="?q=<?= urlencode($query) ?>&n=<?= $items_per_page ?>&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?q=<?= urlencode($query) ?>&n=<?= $items_per_page ?>&page=<?= $page + 1 ?>" aria-label="Next">
                                <span aria-hidden="true">Next &raquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php else: ?>
            <p class="text-center mt-4">No results found.</p>
        <?php endif; ?>
    </div>
    <?php include('footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
