<?php
$pageTitle = 'HIKI - Hiking Guide';
$pageActive = 'guide';
$extraStyles = ['css/pages/hiking-guide.css'];
include __DIR__ . '/includes/header.php';

$guideSections = [
    [
        'title' => 'Prepare the trail day',
        'description' => 'Start with weather, distance, elevation gain, daylight hours, and trail conditions so the plan matches the real effort of the hike.',
    ],
    [
        'title' => 'Choose the right gear',
        'description' => 'Good boots, layered clothing, water, navigation, food, and a small first-aid kit make the biggest difference on the trail.',
    ],
    [
        'title' => 'Hike with safety',
        'description' => 'Tell someone where you are going, stay on marked trails, pace yourself, and turn around early if conditions become unsafe.',
    ],
];

$activityCards = [
    ['name' => 'Summit day hike', 'level' => 'Moderate', 'details' => 'A classic mountain route with elevation gain, scenic viewpoints, and a strong cardio workout.'],
    ['name' => 'Forest loop walk', 'level' => 'Beginner', 'details' => 'Gentle terrain, shaded paths, and a great option for families, recovery days, or short weekend outings.'],
    ['name' => 'Sunrise ridge hike', 'level' => 'Moderate', 'details' => 'Early start, cooler temperatures, and excellent visibility. Pack a headlamp and extra layers.'],
    ['name' => 'Nature photography trek', 'level' => 'Easy', 'details' => 'A slower-paced hike focused on landscape photography, wildlife spotting, and time at scenic stops.'],
    ['name' => 'Trail endurance challenge', 'level' => 'Advanced', 'details' => 'Longer distance, heavier pack weight, and more demanding terrain for hikers building stamina.'],
    ['name' => 'Waterfall route', 'level' => 'Easy', 'details' => 'Shorter hikes that reward the effort with cool rest spots, great for hot weather and group outings.'],
];

$gearGroups = [
    ['title' => 'Clothing', 'items' => ['Moisture-wicking base layer', 'Light insulation layer', 'Waterproof shell', 'Hiking socks', 'Trail shoes or boots', 'Hat and gloves in cold weather']],
    ['title' => 'Navigation and safety', 'items' => ['Paper map or offline map', 'Fully charged phone', 'Power bank', 'Headlamp or flashlight', 'Whistle', 'Small first-aid kit']],
    ['title' => 'Food and hydration', 'items' => ['Water bottle or reservoir', 'Electrolytes', 'Energy snacks', 'Lunch or trail meal', 'Extra water for hot days', 'Insulated thermos for cold trips']],
];

$activityTips = [
    'Keep your pace steady and sustainable instead of starting too fast.',
    'Use trekking poles on steep climbs or descents to reduce knee stress.',
    'Stop for short hydration breaks before you feel tired or thirsty.',
    'Plan your turnaround time before you start the hike.',
    'Leave no trace: carry all trash out and avoid damaging plants or wildlife.',
];
?>

    <main class="guide-shell">
        <section class="hero">
            <p class="eyebrow">Community guide</p>
            <h1>Hiking Guide</h1>
            <p class="lead">A complete hiking companion with trail planning, gear advice, activity ideas, safety practices, and the essentials you need before stepping onto the path.</p>
            <div class="hero-actions">
                <a href="#gear" class="button primary">Gear checklist</a>
                <a href="#activities" class="button secondary">Activity ideas</a>
            </div>
        </section>

        <section class="intro-grid" aria-label="Guide highlights">
            <?php foreach ($guideSections as $section): ?>
                <article class="highlight-card">
                    <h2><?= htmlspecialchars($section['title']) ?></h2>
                    <p><?= htmlspecialchars($section['description']) ?></p>
                </article>
            <?php endforeach; ?>
        </section>

        <section class="content-grid">
            <article class="panel wide" id="guide">
                <div class="panel-heading">
                    <p class="section-label">How to start</p>
                    <h2>Plan every hike before you leave</h2>
                </div>
                <div class="copy-grid">
                    <p>Good hiking starts long before the trailhead. Check the route length, elevation gain, weather forecast, trail closures, sunset time, and water availability. Match the hike to the slowest person in your group, and build a buffer for breaks, navigation, and unexpected delays.</p>
                    <p>For beginners, choose short loop trails with clear signage and a well-marked return point. As experience grows, you can add distance, elevation, or heavier packs. The smartest hikers always plan for the worst likely weather, not the best case forecast.</p>
                </div>
            </article>

            <aside class="panel side-note">
                <p class="section-label">Quick rules</p>
                <ul class="rule-list">
                    <li>Tell someone your route and return time.</li>
                    <li>Carry more water than you think you need.</li>
                    <li>Turn around if storms, fog, or darkness arrive early.</li>
                    <li>Stay on marked trails to protect yourself and nature.</li>
                </ul>
            </aside>
        </section>

        <section class="panel" id="gear">
            <div class="panel-heading">
                <p class="section-label">Materials needed</p>
                <h2>Hiking gear checklist</h2>
            </div>
            <div class="accordion-list">
                <?php foreach ($gearGroups as $index => $group): ?>
                    <details class="accordion-item" <?= $index === 0 ? 'open' : '' ?> data-accordion>
                        <summary>
                            <span><?= htmlspecialchars($group['title']) ?></span>
                            <span class="accordion-icon" aria-hidden="true">+</span>
                        </summary>
                        <ul>
                            <?php foreach ($group['items'] as $item): ?>
                                <li><?= htmlspecialchars($item) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </details>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="panel" id="activities">
            <div class="panel-heading">
                <p class="section-label">Activities</p>
                <h2>Hiking activities you can do</h2>
            </div>
            <div class="activity-filters" role="tablist" aria-label="Filter hiking activities">
                <button type="button" class="filter-button is-active" data-filter="all">All</button>
                <button type="button" class="filter-button" data-filter="Easy">Easy</button>
                <button type="button" class="filter-button" data-filter="Moderate">Moderate</button>
                <button type="button" class="filter-button" data-filter="Advanced">Advanced</button>
            </div>
            <div class="activity-grid" data-activity-grid>
                <?php foreach ($activityCards as $card): ?>
                    <article class="activity-card" data-level="<?= htmlspecialchars($card['level']) ?>">
                        <span class="badge"><?= htmlspecialchars($card['level']) ?></span>
                        <h3><?= htmlspecialchars($card['name']) ?></h3>
                        <p><?= htmlspecialchars($card['details']) ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="content-grid">
            <article class="panel">
                <div class="panel-heading">
                    <p class="section-label">Safety</p>
                    <h2>Trail safety and emergency habits</h2>
                </div>
                <ul class="check-list">
                    <li>Wear clothing in layers so you can adapt to changing weather.</li>
                    <li>Bring a map, compass, or offline GPS and know how to use them.</li>
                    <li>Pack a small first-aid kit with blister care, bandages, and antiseptic wipes.</li>
                    <li>Carry an emergency whistle and battery backup for your phone.</li>
                    <li>Eat and drink regularly to avoid fatigue and poor decisions.</li>
                </ul>
            </article>

            <article class="panel">
                <div class="panel-heading">
                    <p class="section-label">Trail etiquette</p>
                    <h2>Leave no trace on every route</h2>
                </div>
                <div class="tip-stack">
                    <?php foreach ($activityTips as $tip): ?>
                        <div class="tip-pill"><?= htmlspecialchars($tip) ?></div>
                    <?php endforeach; ?>
                </div>
            </article>
        </section>

        <section class="panel summary-panel">
            <div class="panel-heading">
                <p class="section-label">Beginner checklist</p>
                <h2>Take this before your first hike</h2>
            </div>
            <div class="summary-grid">
                <div>
                    <h3>Before departure</h3>
                    <p>Choose an easy route, pack enough water, and confirm the forecast and trail conditions.</p>
                </div>
                <div>
                    <h3>On the trail</h3>
                    <p>Walk at a steady pace, stay aware of landmarks, and rest before you become exhausted.</p>
                </div>
                <div>
                    <h3>After the hike</h3>
                    <p>Check your gear, treat any blisters, rehydrate, and note what you would improve next time.</p>
                </div>
            </div>
        </section>
    </main>

    <script src="/projet-web-gl21-chabiba/js/hiking-guide.js"></script>
    <script src="/projet-web-gl21-chabiba/js/main.js"></script>
</div>
</body>
</html>