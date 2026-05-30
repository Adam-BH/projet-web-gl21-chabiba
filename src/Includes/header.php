<?php
$pageTitle = $pageTitle ?? 'HIKI';
$pageActive = $pageActive ?? '';
$extraStyles = $extraStyles ?? [];

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$navItems = [
    ['key' => 'home', 'label' => 'home', 'href' => '/public_html/index.php'],
    ['key' => 'weather', 'label' => 'weather', 'href' => '/public_html/weather.php'],
    ['key' => 'guide', 'label' => 'hiking guide', 'href' => '/public_html/hiking-guide.php'],
    ['key' => 'equipment', 'label' => 'equipment', 'href' => '/public_html/equipment.php'],
    ['key' => 'moon', 'label' => 'moon', 'href' => '/public_html/moon.php'],
    ['key' => 'catalogue', 'label' => 'camping sites', 'href' => '/public_html/catalogue/index.php'],
];

if (!empty($_SESSION['is_logged'])) {
    $navItems[] = ['key' => 'bookings', 'label' => 'my bookings', 'href' => '/public_html/bookings.php'];
}

$navItems[] = ['key' => 'lostfound', 'label' => 'Lost & found', 'href' => '/public_html/lost-and-found/lost-and-found.php'];
?>
<!doctype html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="/node_modules/bootstrap/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/css/site-shell.css" />
    <?php foreach ($extraStyles as $style): ?>
        <link rel="stylesheet" href="/<?= htmlspecialchars($style) ?>" />
    <?php endforeach; ?>
</head>
<body class="<?= htmlspecialchars($bodyClass ?? '') ?>">
    <canvas id="starCanvas" aria-hidden="true"></canvas>
    <?php if (!isset($hideNavbar) || !$hideNavbar): ?>
    <nav class="navbar navbar-dark" role="navigation" aria-label="Main navigation">
        <div class="navbar-inner">
            <a href="/projet-web-gl21-chabiba/public_html/index.php" class="navbar-brand">HIKI</a>
            <button class="navbar-toggler" type="button" aria-label="Toggle navigation" aria-expanded="false">
                <span class="navbar-toggler-bar"></span>
                <span class="navbar-toggler-bar"></span>
                <span class="navbar-toggler-bar"></span>
            </button>
            <div class="navbar-collapse">
                <ul class="nav-links">
                    <?php foreach ($navItems as $item): ?>
                        <li class="nav-item">
                            <a href="<?= htmlspecialchars($item['href']) ?>" class="nav-link<?= $pageActive === $item['key'] ? ' active' : '' ?>"><?= htmlspecialchars($item['label']) ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="nav-auth">
                    <?php if (isset($_SESSION['is_logged']) && $_SESSION['is_logged'] === true): ?>
                        <span class="nav-user">Hi, <?= htmlspecialchars($_SESSION['user'] ?? 'User') ?></span>
                        <a href="/projet-web-gl21-chabiba/public_html/auth/logout.php" class="nav-btn outline">Logout</a>
                    <?php else: ?>
                        <a href="/projet-web-gl21-chabiba/public_html/auth/login.php" class="nav-btn outline">Login</a>
                        <a href="/projet-web-gl21-chabiba/public_html/auth/signup.php" class="nav-btn primary">Sign Up</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    <?php endif; ?>
    <div class="page-shell">
        <?php include __DIR__ . '/booking-popup.php'; ?>
