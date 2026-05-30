<?php
require_once __DIR__ . '/../../autoloader.php';

$pageTitle = 'HIKI - Camping Sites Explorer';
$pageActive = 'catalogue';
$extraStyles = ['css/pages/catalogue.css', 'css/pages/search-engine.css'];
include __DIR__ . '/../../src/Includes/header.php';
?>

<main class="catalogue-shell search-engine-shell">
    <div class="content-card mb-4">
        <h1 class="page-title">Camping Sites Explorer</h1>
        <p class="page-subtitle">Choose a map area, optional dates, and group size to discover the best available campsites.</p>
    </div>

    <div id="stepper" class="mb-3">
        <div class="steps d-flex gap-2 flex-wrap">
            <button class="btn step-btn active" data-step="1">1. Map</button>
            <button class="btn step-btn" data-step="2">2. Dates</button>
            <button class="btn step-btn" data-step="3">3. People</button>
        </div>
    </div>

    <div id="step-1" class="step">
        <div class="row">
            <div class="col-lg-8">
                <div id="map" class="search-map"></div>
            </div>
            <div class="col-lg-4 mt-3 mt-lg-0">
                <div class="form-group mb-3">
                    <label class="form-label" for="use-location">Use my location</label>
                    <div>
                        <input type="checkbox" id="use-location" checked>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label" for="radius">Radius (km)</label>
                    <div class="d-flex align-items-center gap-3">
                        <input type="range" id="radius" class="form-range" min="0" max="50" step="5" value="0">
                        <span id="radiusValue" class="fw-semibold">No preference</span>
                    </div>
                </div>
                <p class="text-muted">Drag the marker to change center.</p>
                <div id="selected-info"></div>
            </div>
        </div>
    </div>

    <div id="step-2" class="step step-hidden">
        <div class="row gy-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label" for="start-date">Start date (optional)</label>
                    <input type="date" id="start-date" class="form-input">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label" for="end-date">End date (optional)</label>
                    <input type="date" id="end-date" class="form-input">
                </div>
            </div>
        </div>
    </div>

    <div id="step-3" class="step step-hidden">
        <div class="form-group">
            <label class="form-label" for="people">How many people?</label>
            <input type="number" id="people" class="form-input" min="1" value="1">
        </div>
    </div>

    <div class="mt-3 d-flex flex-wrap gap-2 search-buttons">
        <button id="prev" class="btn btn-secondary">Back</button>
        <button id="next" class="btn btn-primary">Next</button>
        <button id="search" class="btn btn-search">Search</button>
    </div>

    <hr>
    <div id="searchPlaceholder" class="info-message">Hit Search to display available camping sites.</div>
    <div id="results" class="step-hidden"></div>
</main>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="/js/search-engine.js"></script>

</div>
</body>
</html>
