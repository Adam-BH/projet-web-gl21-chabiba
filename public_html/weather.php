<?php
require_once __DIR__ . '/../src/Classes/WeatherService.php';

$requestedCity = trim((string)($_GET['city'] ?? 'Tunis'));
$forecast = (new WeatherService())->getForecast($requestedCity);

$todayShort = strtoupper(date('D'));
$selectedShort = strtoupper((string)($_GET['day'] ?? $todayShort));

$selectedDay = null;
foreach ($forecast['days'] as $day) {
    if ($day['short'] === $selectedShort) {
        $selectedDay = $day;
        break;
    }
}
if ($selectedDay === null) {
    $selectedDay = $forecast['days'][0];
}
$pageTitle = 'HIKI — Weather';
$pageActive = 'weather';
$bodyClass = 'hiki-page';
$extraStyles = ['css/shared/hiki-page.css', 'css/pages/weather.css'];
include __DIR__ . '/../src/Includes/header.php';
?>

    <main class="weather-main">
        <h1 class="weather-title">The Weather</h1>

        <div class="weather-grid">
            <section class="weather-card" aria-label="Selected day">
                <img class="weather-card__moon" src="/assets/Images/night-moon-component.png" alt="">
                <div class="weather-card__info">
                    <p class="weather-card__temp"><?= htmlspecialchars((string)$selectedDay['temp']) ?>&deg;</p>
                    <p class="weather-card__country"><?= htmlspecialchars($forecast['country'] ?: 'Tunisia') ?></p>
                    <p class="weather-card__city">CUN, <?= htmlspecialchars($forecast['city']) ?></p>
                    <p class="weather-card__wind">
                        <?= htmlspecialchars($selectedDay['wind']) ?> km/h Wind
                    </p>
                </div>
            </section>

            <section class="weather-panel">
                <ul class="forecast-row" aria-label="7-day forecast">
                    <?php foreach ($forecast['days'] as $day): ?>
                        <?php $isActive = $day['short'] === $selectedDay['short']; ?>
                        <li class="forecast-pill <?= $isActive ? 'is-active' : '' ?>">
                            <a class="forecast-pill__link"
                               href="?city=<?= urlencode($forecast['city']) ?>&amp;day=<?= urlencode($day['short']) ?>"
                               title="<?= htmlspecialchars($day['label']) ?>">
                                <span class="forecast-pill__day"><?= htmlspecialchars($day['short']) ?></span>
                                <img class="forecast-pill__icon"
                                     src="/assets/Images/<?= htmlspecialchars($day['icon']) ?>" alt="">
                                <span class="forecast-pill__rain"><?= htmlspecialchars((string)$day['rain']) ?>%</span>
                                <span class="forecast-pill__temp"><?= htmlspecialchars((string)$day['temp']) ?>&deg;</span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <form class="weather-search" method="get" action="weather.php" role="search">
                    <input class="weather-search__input"
                           type="text" name="city" placeholder="Your Location ...."
                           value="<?= htmlspecialchars($requestedCity) ?>"
                           autocomplete="off">
                    <button class="weather-search__btn" type="submit" aria-label="Search">
                        <img src="/assets/Images/search-icon.svg" alt="">
                    </button>
                </form>
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
    </div>
</body>
</html>
