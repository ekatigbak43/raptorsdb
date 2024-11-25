<?php
require('connect.php');
session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'editor')) {
    header("Location: login.php");
    exit;
}

if ($_GET && isset($_GET['id']) && is_numeric($_GET['id'])) {
    $article_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    $query = "SELECT * FROM articles WHERE article_id = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $article_id, PDO::PARAM_INT);
    $statement->execute();
    $article = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$article) {
        $error = "Article not found.";
    }
} else {
    $error = "Invalid or missing article ID.";
}

if ($_POST && isset($_POST['title'], $_POST['content'])) {
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $image = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = uniqid() . '-' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/$image");
    }

    if (isset($_POST['delete_image']) && $_POST['delete_image'] === 'on') {
        if (!empty($article['image']) && file_exists("uploads/" . $article['image'])) {
            unlink("uploads/" . $article['image']);
        }
        $image = null;
    }

    $update_query = "UPDATE articles SET title = :title, content = :content, image = :image WHERE article_id = :id";
    $update_statement = $db->prepare($update_query);
    $update_statement->bindValue(':title', $title);
    $update_statement->bindValue(':content', $content);
    $update_statement->bindValue(':image', $image !== null ? $image : $article['image']);
    $update_statement->bindValue(':id', $article_id, PDO::PARAM_INT);

    if ($update_statement->execute()) {
        if (isset($_POST['delete_image']) && $_POST['delete_image'] === 'on') {
            $image = null;
        }

        header("Location: view_article.php?id=$article_id");
        exit;
    } else {
        $error = "Failed to update the article.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Edit Article</title>
</head>
<body>
    <?php include('nav.php'); ?>

    <div class="container my-5">
        <h1 class="text-center">Edit Article</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php else: ?>
            <form action="edit_article.php?id=<?= $article_id ?>" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($article['title']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">Content</label>
                    <textarea id="content" name="content" class="form-control" rows="5" required><?= htmlspecialchars($article['content']) ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Image (optional)</label>
                    <input type="file" id="image" name="image" class="form-control">
                    <?php if (!empty($article['image'])): ?>
                        <p>Current Image: <img src="uploads/<?= htmlspecialchars($article['image']) ?>" alt="" class="img-thumbnail" width="150"></p>
                        <div class="form-check">
                            <input type="checkbox" id="delete_image" name="delete_image" class="form-check-input">
                            <label for="delete_image" class="form-check-label">Delete current image</label>
                        </div>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary">Update Article</button>
            </form>
        <?php endif; ?>
    </div>

    <?php include('footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
