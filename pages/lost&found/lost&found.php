<?php session_start();
$name = isset($_SESSION["user"]) ? $_SESSION["user"] : "";
?>
<!doctype html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>HIKI - Lost & Found</title>
    <link rel="stylesheet" href="../../css/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/pages/l&f.css" />
</head>

<body>
    <canvas id="starCanvas" style="position:fixed;inset:0;z-index:0;pointer-events:none;"></canvas>
    <nav>
        <a href="../../../" class="navbar-brand">HIKI</a>
        <div class="hero-title">Welcome <?= htmlspecialchars($name) ?>! Did you lost something?</div>
        <?php if (isset($_SESSION["is_logged"]) && $_SESSION["is_logged"]): ?>
            <a href="add_post.php" class="add-btn glowy-btn">+ Post New Item</a>
        <?php endif; ?>
    </nav>
    <div class="frame">
        <div class="posts-wrapper">
            <?php include_once "itemlist.php"; ?>
        </div>
    </div>
    <script src="../../js/l&f.js"></script>
    <script src="../../js/main.js"></script>
</body>

</html>