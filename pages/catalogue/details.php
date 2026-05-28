<?php
require_once __DIR__ . '/../autoloader.php';

$pageTitle = 'HIKI - Site Details';
$pageActive = 'catalogue';
$extraStyles = ['css/pages/catalogue.css'];
include __DIR__ . '/../includes/header.php';

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

<main class="catalogue-shell" style="max-width:980px;margin:24px auto;padding:0 16px;">
    <a href="/projet-web-gl21-chabiba/pages/catalogue/index.php" style="display:inline-block;margin-bottom:12px;">← Back to catalogue</a>

    <?php if ($error): ?>
        <div class="error-message"><strong>Error:</strong> <?= htmlspecialchars($error) ?></div>
    <?php else: ?>
        <section class="site-details">
            <h1><?= htmlspecialchars($site->name ?? 'Untitled') ?></h1>
            <p class="muted"><?= htmlspecialchars($site->city ?? '') ?> · Capacity: <?= htmlspecialchars($site->capacity ?? '-') ?></p>

            <div class="site-media" style="display:flex;gap:12px;margin-top:18px;">
                <div class="media-carousel" style="flex:1;min-width:260px;">
                    <?php
                    $images = [];
                    if (!empty($site->images) && is_array($site->images) && count($site->images)) {
                        $images = $site->images;
                    } elseif (!empty($site->image)) {
                        $images = [$site->image];
                    }
                    ?>

                    <?php if (count($images)): ?>
                        <div class="carousel-main" style="border-radius:12px;overflow:hidden;border:1px solid rgba(255,255,255,0.06);">
                            <img id="mainImage" src="<?= htmlspecialchars($images[0]) ?>" alt="<?= htmlspecialchars($site->name) ?>" style="width:100%;height:auto;display:block;">
                        </div>
                        <div class="carousel-thumbs" style="display:flex;gap:8px;margin-top:8px;flex-wrap:wrap;">
                            <?php foreach($images as $img): ?>
                                <button class="thumb" type="button" data-src="<?= htmlspecialchars($img) ?>" style="border:0;background:transparent;padding:0;cursor:pointer;">
                                    <img src="<?= htmlspecialchars($img) ?>" alt="thumb" style="width:80px;height:60px;object-fit:cover;border-radius:6px;border:1px solid rgba(255,255,255,0.04);">
                                </button>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div style="padding:36px;border-radius:12px;border:1px solid rgba(255,255,255,0.06);">No images available</div>
                    <?php endif; ?>
                </div>

                <div class="site-info" style="flex:1;min-width:260px;">
                    <h2>Description</h2>
                    <p><?= nl2br(htmlspecialchars($site->description ?? 'No description available')) ?></p>

                    <h3>Location</h3>
                    <p><?= htmlspecialchars($site->city ?? 'Unknown') ?>
                    <?php if ($site->lat && $site->lon): ?>
                        <br>Coordinates: <?= htmlspecialchars($site->lat) ?>, <?= htmlspecialchars($site->lon) ?>
                    <?php endif; ?></p>

                    <div style="margin-top:14px;">
                        <a href="#" class="site-card-button">Book Now</a>
                    </div>
                </div>
            </div>
        </section>

        <script>
            (function(){
                const main = document.getElementById('mainImage');
                document.querySelectorAll('.thumb').forEach(btn => {
                    btn.addEventListener('click', function(){
                        const src = this.dataset.src;
                        if (src && main) main.src = src;
                    });
                });
            })();
        </script>
    <?php endif; ?>
</main>
