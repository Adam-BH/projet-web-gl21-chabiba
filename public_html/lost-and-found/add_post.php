<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/../../autoloader.php';
$errors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_errors']);

$campingSiteRepository = new CampingSiteRepository();
$campingSites = $campingSiteRepository->findAll();

$pageTitle = 'HIKI - Add Post';
$pageActive = 'lostfound';
$extraStyles = ['css/pages/lost-and-found.css'];
include __DIR__ . '/../../src/Includes/header.php';
?>

<main class="guide-shell">
    <section class="hero text-center">
        <p class="eyebrow">Lost &amp; Found</p>
        <h1>Post a Found Item</h1>
        <p class="lead">Found something at a campsite? Fill out the form below so the owner can get it back.</p>

        <form action="save_post.php" enctype="multipart/form-data" method="POST" class="glass-panel mt-4" style="max-width: 560px; text-align: left;">
            <div class="form-group" style="margin-bottom: 18px;">
                <label for="itemInput">What did you find?</label>
                <input type="text" id="itemInput" name="item" maxlength="50" required class="glass-input" placeholder="e.g. Water bottle, backpack...">
                <?php if (!empty($errors['item'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['item']) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group" style="margin-bottom: 18px;">
                <label for="placeSelect">Camping Site</label>
                <select id="placeSelect" name="place" required class="glass-input">
                    <option value="" disabled selected>Select a camping site</option>
                    <?php foreach ($campingSites as $site): ?>
                        <option value="<?= htmlspecialchars($site->name) ?>"><?= htmlspecialchars($site->name) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (!empty($errors['place'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['place']) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group" style="margin-bottom: 22px;">
                <label for="imageInput">Photo (optional)</label>
                <input type="file" id="imageInput" name="image" accept="image/jpeg,image/png,image/webp" class="glass-input" style="padding: 10px;">
            </div>

            <button type="submit" class="button primary w-100">Submit Post</button>
        </form>
    </section>
</main>

<script src="/projet-web-gl21-chabiba/js/pages/lost-and-found.js"></script>
<script src="/projet-web-gl21-chabiba/js/main.js"></script>
</div>
</body>
</html>