<?php
require('authenticate.php');
require('connect.php');
session_start();

if ($_POST && !empty($_POST['title']) && !empty($_POST['content'])) {
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $author_id = $_SESSION['user_id'];

    $query = "INSERT INTO articles (title, content, author_id, created_at) VALUES (:title, :content, :author_id, NOW())";
    $statement = $db->prepare($query);

    $statement->bindValue(':title', $title);
    $statement->bindValue(':content', $content);
    $statement->bindValue(':author_id', $author_id);

    if ($statement->execute()) {
        echo "Article successfully created!";
    } else {
        echo "Failed to create the article.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Article</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include('nav.php'); ?>
    <div class="container mt-5">
        <h1>Create a New Article</h1>
        <form method="post" action="create_article.php">
            <div class="mb-3">
                <label for="title" class="form-label">Title:</label>
                <input id="title" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Content:</label>
                <textarea id="content" name="content" rows="5" class="form-control" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <?php include('footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
