<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">RaptorsDB</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="raptors.php">Raptors Database</a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'editor'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="create_article.php">Create Article</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout (<?= htmlspecialchars($_SESSION['username']) ?>)</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
