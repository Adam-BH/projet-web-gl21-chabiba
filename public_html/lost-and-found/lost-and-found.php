<?php
session_start();
$name = isset($_SESSION["user"]) ? $_SESSION["user"] : "";
$pageTitle = 'HIKI - Lost & Found';
$pageActive = 'lostfound';
$extraStyles = ['css/pages/lost-and-found.css'];
include __DIR__ . '/../../src/Includes/header.php';
?>

<main class="lost-found-shell">
    <section class="hero text-center">
        <p class="eyebrow">Community</p>
        <h1 class="page-title">Lost &amp; Found</h1>
        <p class="page-subtitle">Welcome <?= htmlspecialchars($name) ?>! Found something at a campsite? Help it get back to its owner.</p>
        <?php if (isset($_SESSION["is_logged"]) && $_SESSION["is_logged"]): ?>
            <div class="hero-actions">
                <a href="add_post.php" class="btn btn-primary">+ Post New Item</a>
            </div>
        <?php endif; ?>
    </section>

    <div class="frame">
        <div class="posts-wrapper">
            <?php include_once "itemlist.php"; ?>
        </div>
    </div>
</main>

<script src="/projet-web-gl21-chabiba/js/pages/lost-and-found.js"></script>
<script src="/projet-web-gl21-chabiba/js/main.js"></script>
</div>
</body>
</html>