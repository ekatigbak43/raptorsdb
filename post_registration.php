<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <?php include('nav.php'); ?>

    <div class="container">
        <h1>Welcome to RaptorsDB!</h1>
        <p>Your registration was successful. You can now log in.</p>
        <a href="login.php" class="button">Go to Login</a>
    </div>
</body>
</html>
