<?php session_start();
$name = isset($_SESSION["user"]) ? $_SESSION["user"] : "";
?>
<!doctype html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>HIKI - Lost & Found</title>
    <link rel="stylesheet" href="../../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/pages/l&f.css" />
</head>

<body>
    <canvas id="starCanvas" style="position:fixed;inset:0;z-index:0;pointer-events:none;"></canvas>
    <div class="hero-title">Welcome <?= htmlspecialchars($name) ?>! Did you lost something?</div>
    <div class="frame">
        <div class="posts-wrapper">
            <?php include_once "itemlist.php"; ?>
        </div>
    </div>
    <script src="../../js/l&f.js"></script>
    <script src="../../js/main.js"></script>
</body>

</html>