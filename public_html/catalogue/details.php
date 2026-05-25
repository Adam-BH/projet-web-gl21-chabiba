<?php
require_once __DIR__ . '/../../autoloader.php';

$pageTitle = 'HIKI - Site Details';
$pageActive = 'catalogue';
$extraStyles = ['css/pages/catalogue.css'];
include __DIR__ . '/../../src/Includes/header.php';

$repo = new CampingSiteRepository();
$site = null;
$error = null;

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
if (!$id) {
    $error = 'Missing site id.';
} else {
    try{
        $site = $repo->findById($id);
        if (!$site) $error = 'Site not found.';
    }catch(Exception $e){
        $error = $e->getMessage();
    }
}
?>

<main class="catalogue-shell site-details-shell">
    <a href="/projet-web-gl21-chabiba/public_html/catalogue/index.php" class="back-link">← Back to catalogue</a>

    <?php if ($error): ?>
        <div class="error-message"><strong>Error:</strong> <?= htmlspecialchars($error) ?></div>
    <?php else: ?>
        <section class="site-details">
            <h1><?= htmlspecialchars($site->name ?? 'Untitled') ?></h1>
            <p class="muted"><?= htmlspecialchars($site->city ?? '') ?> · Capacity: <?= htmlspecialchars($site->capacity ?? '-') ?></p>

            <div class="site-media">
                <div class="media-carousel">
                    <?php
                    $images = [];
                    if (!empty($site->images) && is_array($site->images) && count($site->images)) {
                        $images = $site->images;
                    } elseif (!empty($site->image)) {
                        $images = [$site->image];
                    }
                    ?>

                    <?php if (count($images)): ?>
                        <div class="carousel-main">
                            <img id="mainImage" src="<?= htmlspecialchars($images[0]) ?>" alt="<?= htmlspecialchars($site->name) ?>">
                        </div>
                        <div class="carousel-thumbs">
                            <?php foreach($images as $img): ?>
                                <button class="thumb" type="button" data-src="<?= htmlspecialchars($img) ?>">
                                    <img src="<?= htmlspecialchars($img) ?>" alt="thumb">
                                </button>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="carousel-empty">No images available</div>
                    <?php endif; ?>
                </div>

                <div class="site-info">
                    <h2>Description</h2>
                    <p><?= nl2br(htmlspecialchars($site->description ?? 'No description available')) ?></p>

                    <h3>Location</h3>
                    <p><?= htmlspecialchars($site->city ?? 'Unknown') ?>
                    <?php if ($site->lat && $site->lon): ?>
                        <br>Coordinates: <?= htmlspecialchars($site->lat) ?>, <?= htmlspecialchars($site->lon) ?>
                    <?php endif; ?></p>

                    <div class="site-details-actions">
                        <a href="#" class="site-card-button book-now-btn" data-site-id="<?= htmlspecialchars($site->id) ?>">Book Now</a>
                    </div>
                </div>
            </div>
        </section>

        <script>
            (function(){
                const main = document.getElementById('mainImage');
                const thumbs = document.querySelectorAll('.thumb');
                if (thumbs.length) thumbs[0].classList.add('is-active');
                thumbs.forEach(btn => {
                    btn.addEventListener('click', function(){
                        const src = this.dataset.src;
                        if (src && main) main.src = src;
                        thumbs.forEach(t => t.classList.remove('is-active'));
                        this.classList.add('is-active');
                    });
                });
            })();
        </script>
    <?php endif; ?>
</main>
</div>
</body>
</html>
