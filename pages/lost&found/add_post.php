<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$errors = $_SESSION['form_errors'] ?? [];
?>
<!doctype html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>HIKI - Sign Up</title>
    <link rel="stylesheet" href="../../css/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/pages/auth.css" />
</head>

<body>
    <canvas id="starCanvas" style="position:fixed;inset:0;z-index:0;pointer-events:none;"></canvas>
    <a href="../../../" class="navbar-brand">HIKI</a>
    <div class="container">
        <p class="login-title">Wecome</p>
        <p class="login-sub">Tell Us More About Your Post</p>
        <form action="save_post.php" enctype="multipart/form-data" method="POST">
            <div class="input-box">
                <input type="text" name="item" maxlength="50" required>
                <label>What did you find?</label>
            </div>
            <div class="input-box">
                <input type="text" name="place" maxlength="20" required>
                <!-- we should turn this into a list of places that the webste has once we figure to link our work -->
                <label>Camping Place</label>
            </div>
            <div>
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp" onchange="previewImage(this)">
            </div>
            <button type="submit" class="btn-login">Submit Post</button>
        </form>
        <?php if (!empty($errors['item'])): ?>
            <div class="error"><?= htmlspecialchars($errors['item']) ?></div>
        <?php endif;
        if (!empty($errors['place'])): ?>
            <div class="error"><?= htmlspecialchars($errors['place']) ?></div>
        <?php endif; ?>
    </div>
    <script src="../../js/auth.js"></script>
    <script src="../../js/main.js"></script>
</body>

</html>