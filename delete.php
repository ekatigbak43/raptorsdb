<?php
require('connect.php');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $article_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    $query = "DELETE FROM articles WHERE article_id = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $article_id, PDO::PARAM_INT);

    if ($statement->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error deleting article.";
    }
} else {
    echo "Invalid or missing article ID.";
}
?>
