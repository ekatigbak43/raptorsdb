<?php
require('connect.php');
session_start();

if ($_POST && isset($_POST['username'], $_POST['password'])) {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = $_POST['password'];

    try {
        $query = "SELECT * FROM users WHERE username = :username";
        $statement = $db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                header("Location: index.php");
                exit;
            } else {
                $error = "Password is incorrect.";
            }
        } else {
            $error = "User not found.";
        }
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<?php include('nav.php'); ?>
<body>
    <h1>Login</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form action="login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
    <?php include('footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>