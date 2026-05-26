<?php
session_start();
$name = isset($_SESSION["user"]) ? $_SESSION["user"] : "";
$pageTitle = 'HIKI - Lost & Found';
$pageActive = 'lostfound';
$extraStyles = ['css/pages/l&f.css'];
include __DIR__ . '/../includes/header.php';
?>

    <div class="hero-title">Welcome <?= htmlspecialchars($name) ?>! Did you lost something?</div>
    <div class="frame">
        <div class="posts-wrapper">
            <?php include_once "itemlist.php"; ?>
        </div>
    </div>
    <script src="/projet-web-gl21-chabiba/js/l&f.js"></script>
    <script src="/projet-web-gl21-chabiba/js/main.js"></script>
</div>
</body>
</html>