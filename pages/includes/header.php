<?php
$pageTitle = $pageTitle ?? 'HIKI';
$pageActive = $pageActive ?? '';
$extraStyles = $extraStyles ?? [];

$navItems = [
    ['key' => 'home', 'label' => 'home', 'href' => '/projet-web-gl21-chabiba/index.php'],
    ['key' => 'weather', 'label' => 'weather', 'href' => '/projet-web-gl21-chabiba/pages/weather.php'],
    ['key' => 'guide', 'label' => 'hiking guide', 'href' => '/projet-web-gl21-chabiba/pages/hiking-guide.php'],
    ['key' => 'equipment', 'label' => 'equipment', 'href' => '/projet-web-gl21-chabiba/pages/equipment.php'],
    ['key' => 'moon', 'label' => 'moon', 'href' => '/projet-web-gl21-chabiba/pages/moon.php'],
    ['key' => 'shops', 'label' => 'shops', 'href' => '/projet-web-gl21-chabiba/pages/shops.html'],
    ['key' => 'lostfound', 'label' => 'Lost & found', 'href' => '/projet-web-gl21-chabiba/pages/lost&found/lost&found.php'],
];
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
    <nav class="navbar navbar-dark" role="navigation" aria-label="Main navigation">
        <div class="container">
            <a href="/projet-web-gl21-chabiba/index.php" class="navbar-brand">HIKI</a>
            <ul class="navbar-nav nav-links">
                <?php foreach ($navItems as $item): ?>
                    <li class="nav-item">
                        <a href="<?= htmlspecialchars($item['href']) ?>" class="nav-link<?= $pageActive === $item['key'] ? ' active' : '' ?>"><?= htmlspecialchars($item['label']) ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </nav>
    <div class="page-shell">