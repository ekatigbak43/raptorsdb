<?php
require('connect.php');

if ($_POST && isset($_POST['username'], $_POST['password'], $_POST['confirm_password'], $_POST['role'])) {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $plaintext_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($plaintext_password === $confirm_password) {
        $hashed_password = password_hash($plaintext_password, PASSWORD_DEFAULT);

        try {
            $query = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
            $statement = $db->prepare($query);
            $statement->bindValue(':username', $username);
            $statement->bindValue(':password', $hashed_password);
            $statement->bindValue(':role', $role);

            if ($statement->execute()) {
                header("Location: post_registration.php");
                exit;
            } else {
                $error = "Error registering user.";
            }
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    } else {
        $error = "Passwords do not match. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include('nav.php'); ?>
    <h1>Register</h1>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="register.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="editor">Editor</option>
            <option value="user">User</option>
        </select>

        <button type="submit" class="btn btn-primary">Register</button>
    </form>
    <?php include('footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>    
</body>
</html>
