<?php
require_once __DIR__ . '/../src/Classes/MoonService.php';
require_once __DIR__ . '/../autoloader.php';

$campingSiteRepo = new CampingSiteRepository();
$campingSites = $campingSiteRepo->findAll();

$service = new MoonService();
$week = $service->getWeek();
$selectedIndex = (int)($_GET['d'] ?? 0);
if ($selectedIndex < 0 || $selectedIndex >= count($week)) {
    $selectedIndex = 0;
}
$selected = $week[$selectedIndex];

$selectedSiteId = (int)($_GET['site_id'] ?? 0);
$selectedSite = null;
if ($selectedSiteId > 0) {
    foreach ($campingSites as $cs) {
        if ((int)$cs->id === $selectedSiteId) {
            $selectedSite = $cs;
            break;
        }
    }
}

$pageTitle = 'HIKI — Moon';
$pageActive = 'moon';
$bodyClass = 'hiki-page';
$extraStyles = ['css/shared/hiki-page.css', 'css/pages/moon.css'];
include __DIR__ . '/../src/Includes/header.php';
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
                    <?php if ($selectedSite): ?>
                        <p class="moon-feature__site">Viewing from: <?= htmlspecialchars($selectedSite->name) ?>, <?= htmlspecialchars($selectedSite->city) ?></p>
                    <?php endif; ?>
                </div>
            </section>

            <section class="moon-panel" aria-label="7-day forecast">
                <ul class="moon-week">
                    <?php foreach ($week as $i => $phase): ?>
                        <?php
                        $linkParams = ['d=' . $i];
                        if ($selectedSiteId > 0) {
                            $linkParams[] = 'site_id=' . $selectedSiteId;
                        }
                        ?>
                        <li class="moon-pill <?= $i === $selectedIndex ? 'is-active' : '' ?>">
                            <a class="moon-pill__link" href="?<?= htmlspecialchars(implode('&amp;', $linkParams)) ?>">
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

                <div class="moon-controls">
                    <form class="moon-site-form" method="get" action="moon.php">
                        <div class="form-group">
                            <label for="moonSiteSelect">Camping Site</label>
                            <select id="moonSiteSelect" name="site_id" class="form-input moon-select" onchange="this.form.submit()">
                                <option value="0">All locations</option>
                                <?php foreach ($campingSites as $site): ?>
                                    <option value="<?= (int)$site->id ?>" <?= $selectedSiteId === (int)$site->id ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($site->name) ?> (<?= htmlspecialchars($site->city) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <input type="hidden" name="d" value="<?= $selectedIndex ?>">
                    </form>
                </div>
            </section>
        </div>
    </main>

    <div class="theme-toggle">
        <input type="checkbox" id="toggle" hidden>
        <label for="toggle" class="toggle-track">
            <div class="toggle-thumb"></div>
        </label>
    </div>

    <script src="/js/main.js"></script>
    <script src="/js/pages/moon.js"></script>
    </div>
</body>
</html>
