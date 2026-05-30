<?php
session_start();
$name = isset($_SESSION["user"]) ? $_SESSION["user"] : "";
$pageTitle = 'HIKI - Lost & Found';
$pageActive = 'lostfound';
$extraStyles = ['css/pages/lost-and-found.css'];
include __DIR__ . '/../../src/Includes/header.php';
?>



    <div class="hero-title">Welcome <?= htmlspecialchars($name) ?>! Did you lost something?</div>
    <?php if (isset($_SESSION["is_logged"]) && $_SESSION["is_logged"]): ?>
        <a href="add_post.php" class="add-btn glowy-btn">+ Post New Item</a>
    <?php endif; ?>
    <div class="frame">
        <div class="posts-wrapper">
            <?php include_once "itemlist.php"; ?>
        </div>
    </div>
    <script src="/js/pages/lost-and-found.js"></script>
    <script src="/js/main.js"></script>
</div>
</body>
</html>