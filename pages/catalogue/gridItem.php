<?php
// Renders a single site as a grid card. Expects $site in scope.
if (!isset($site)) return;
?>
<article class="site-card" 
    data-id="<?= htmlspecialchars($site->id) ?>"
    data-name="<?= htmlspecialchars($site->name ?? '') ?>"
    data-city="<?= htmlspecialchars($site->city ?? '') ?>"
    data-capacity="<?= (int)($site->capacity ?? 0) ?>"
    data-lat="<?= is_numeric($site->lat) ? $site->lat : '' ?>"
    data-lon="<?= is_numeric($site->lon) ? $site->lon : '' ?>"
    data-description="<?= htmlspecialchars($site->description ?? '') ?>">
    <div class="site-card-image">
        <?php
        $thumb = '';
        if (!empty($site->images) && is_array($site->images) && count($site->images)) {
            $thumb = $site->images[0];
        } elseif (!empty($site->image)) {
            $thumb = $site->image;
        }
        ?>
        <?php if ($thumb): ?>
            <img src="<?= htmlspecialchars($thumb) ?>" alt="<?= htmlspecialchars($site->name) ?>">
        <?php else: ?>
            <span>No image available</span>
        <?php endif; ?>
    </div>
    <div class="site-card-content">
        <div class="site-card-header">
            <h2 class="site-card-title"><?= htmlspecialchars($site->name ?? 'Untitled') ?></h2>
            <p class="site-card-city"><?= htmlspecialchars($site->city ?? 'Location unavailable') ?></p>
            <p class="site-distance" aria-hidden="true"></p>
        </div>
        <div class="site-card-meta">
            <div class="site-meta-item">
                <span class="site-meta-label">Capacity:</span>
                <span><?= htmlspecialchars($site->capacity ?? '-') ?></span>
            </div>
        </div>
        <p class="site-card-description"><?= nl2br(htmlspecialchars($site->description ?? 'No description available')) ?></p>
        <div class="site-card-actions">
            <a href="/projet-web-gl21-chabiba/pages/catalogue/details.php?id=<?= urlencode($site->id) ?>" class="site-card-button">View Details</a>
            <a href="#" class="site-card-button book-now-btn" data-site-id="<?= htmlspecialchars($site->id) ?>">Book Now</a>
        </div>
    </div>
</article>
