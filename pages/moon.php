<?php
require_once __DIR__ . '/../class/MoonService.php';

$service = new MoonService();
$week = $service->getWeek();
$selectedIndex = (int)($_GET['d'] ?? 0);
if ($selectedIndex < 0 || $selectedIndex >= count($week)) {
    $selectedIndex = 0;
}
$selected = $week[$selectedIndex];
$pageTitle = 'HIKI — Moon';
$pageActive = 'moon';
$bodyClass = 'hiki-page';
$extraStyles = ['css/shared/hiki-page.css', 'css/pages/moon-page.css'];
include __DIR__ . '/includes/header.php';
?>

    <main class="moon-main">
        <h1 class="moon-title">The Moon</h1>

        <div class="moon-grid">
            <section class="moon-feature" aria-label="Featured phase">
                <div class="moon-disc moon-disc--large js-moon-disc"
                     data-shadow-side="<?= htmlspecialchars($selected['shadow_side']) ?>"
                     data-ellipse-scale="<?= htmlspecialchars((string)$selected['ellipse_scale']) ?>"
                     data-ellipse-fill="<?= htmlspecialchars($selected['ellipse_fill']) ?>">
                    <span class="moon-disc__half moon-disc__half--<?= htmlspecialchars($selected['shadow_side']) ?>"></span>
                    <span class="moon-disc__ellipse"></span>
                </div>
                <div class="moon-feature__info">
                    <p class="moon-feature__day"><?= $selectedIndex === 0 ? 'Tonight' : htmlspecialchars($selected['long']) ?></p>
                    <p class="moon-feature__name"><?= htmlspecialchars($selected['name']) ?></p>
                    <p class="moon-feature__illum"><?= (int)$selected['illumination'] ?>% illuminated</p>
                    <p class="moon-feature__age">Lunar age: <?= htmlspecialchars((string)$selected['age_days']) ?> days</p>
                    <p class="moon-feature__hint"><?= htmlspecialchars($selected['visibility']) ?></p>
                </div>
            </section>

            <section class="moon-panel" aria-label="7-day forecast">
                <ul class="moon-week">
                    <?php foreach ($week as $i => $phase): ?>
                        <li class="moon-pill <?= $i === $selectedIndex ? 'is-active' : '' ?>">
                            <a class="moon-pill__link" href="?d=<?= $i ?>">
                                <span class="moon-pill__day">
                                    <?= $i === 0 ? 'TODAY' : htmlspecialchars($phase['short']) ?>
                                </span>
                                <span class="moon-disc moon-disc--small js-moon-disc"
                                      data-shadow-side="<?= htmlspecialchars($phase['shadow_side']) ?>"
                                      data-ellipse-scale="<?= htmlspecialchars((string)$phase['ellipse_scale']) ?>"
                                      data-ellipse-fill="<?= htmlspecialchars($phase['ellipse_fill']) ?>">
                                    <span class="moon-disc__half moon-disc__half--<?= htmlspecialchars($phase['shadow_side']) ?>"></span>
                                    <span class="moon-disc__ellipse"></span>
                                </span>
                                <span class="moon-pill__name"><?= htmlspecialchars($phase['short_name']) ?></span>
                                <span class="moon-pill__illum"><?= (int)$phase['illumination'] ?>%</span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        </div>
    </main>

    <div class="theme-toggle">
        <input type="checkbox" id="toggle" hidden>
        <label for="toggle" class="toggle-track">
            <div class="toggle-thumb"></div>
        </label>
    </div>

    <script src="../js/main.js"></script>
    <script src="../js/moon-page.js"></script>
    </div>
</body>
</html>
