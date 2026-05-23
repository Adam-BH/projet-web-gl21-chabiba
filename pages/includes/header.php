<?php
$pageTitle = $pageTitle ?? 'HIKI';
$pageActive = $pageActive ?? '';
$extraStyles = $extraStyles ?? [];

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$navItems = [
    ['key' => 'home', 'label' => 'home', 'href' => '/projet-web-gl21-chabiba/index.php'],
    ['key' => 'weather', 'label' => 'weather', 'href' => '/projet-web-gl21-chabiba/pages/weather.php'],
    ['key' => 'guide', 'label' => 'hiking guide', 'href' => '/projet-web-gl21-chabiba/pages/hiking-guide.php'],
    ['key' => 'equipment', 'label' => 'equipment', 'href' => '/projet-web-gl21-chabiba/pages/equipment.php'],
    ['key' => 'moon', 'label' => 'moon', 'href' => '/projet-web-gl21-chabiba/pages/moon.php'],
    ['key' => 'shops', 'label' => 'shops', 'href' => '/projet-web-gl21-chabiba/pages/shops.html'],
    ['key' => 'catalogue', 'label' => 'camping sites', 'href' => '/projet-web-gl21-chabiba/pages/catalogue/index.php'],
];

if (!empty($_SESSION['is_logged'])) {
    $navItems[] = ['key' => 'bookings', 'label' => 'my bookings', 'href' => '/projet-web-gl21-chabiba/pages/bookings.php'];
}

$navItems[] = ['key' => 'lostfound', 'label' => 'Lost & found', 'href' => '/projet-web-gl21-chabiba/pages/lost&found/lost&found.php'];
?>
<!doctype html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="/projet-web-gl21-chabiba/node_modules/bootstrap/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/projet-web-gl21-chabiba/css/site-shell.css" />
    <?php foreach ($extraStyles as $style): ?>
        <link rel="stylesheet" href="/projet-web-gl21-chabiba/<?= htmlspecialchars($style) ?>" />
    <?php endforeach; ?>
</head>
<body class="<?= htmlspecialchars($bodyClass ?? '') ?>">
    <canvas id="starCanvas" aria-hidden="true"></canvas>
    <?php if (!isset($hideNavbar) || !$hideNavbar): ?>
    <nav class="navbar navbar-dark" role="navigation" aria-label="Main navigation">
        <div class="container d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center" style="gap: 2rem;">
                <a href="/projet-web-gl21-chabiba/index.php" class="navbar-brand">HIKI</a>
                <ul class="navbar-nav nav-links flex-row m-0">
                    <?php foreach ($navItems as $item): ?>
                        <li class="nav-item">
                            <a href="<?= htmlspecialchars($item['href']) ?>" class="nav-link<?= $pageActive === $item['key'] ? ' active' : '' ?>"><?= htmlspecialchars($item['label']) ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="nav-auth ms-3 d-flex align-items-center">
                <?php if (isset($_SESSION['is_logged']) && $_SESSION['is_logged'] === true): ?>
                    <span class="text-light me-3">Hi, <?= htmlspecialchars($_SESSION['user'] ?? 'User') ?></span>
                    <a href="/projet-web-gl21-chabiba/pages/auth/logout.php" class="nav-btn outline">Logout</a>
                <?php else: ?>
                    <a href="/projet-web-gl21-chabiba/pages/auth/login.php" class="nav-btn outline me-2">Login</a>
                    <a href="/projet-web-gl21-chabiba/pages/auth/signup.php" class="nav-btn primary">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <?php endif; ?>
    <div class="page-shell">
        <?php include __DIR__ . '/booking-popup.php'; ?>