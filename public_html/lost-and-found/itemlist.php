<?php
require_once __DIR__ . '/../../autoloader.php';

try {
    $postRepository = new PostRepository();
    $posts = $postRepository->findAll();
} catch (Throwable $e) {
    $posts = [];
}

if (!$posts || count($posts) === 0) {
    echo 'No Found Items Here.';
} else {
    foreach ($posts as $post): ?>
        <div class="post-card">
            <?php
            $photoPath =  $post->picture;
            ?>

            <img src="<?= htmlspecialchars($photoPath) ?>" alt="Item Picture">
            <div class="post-content">
                <div class="item">
                    Item: <?= htmlspecialchars(strtoupper($post->item)) ?>
                </div>
                <div class="place">
                    Found in: <?= htmlspecialchars($post->place) ?>
                </div>
                <div class="date">
                    Posted on: <?= htmlspecialchars(strtoupper($post->created_at)) ?>
                </div>
                <div class="phone">
                    To restore it call: <?= htmlspecialchars(strtoupper($post->phone)) ?>
                </div>
                <?php if (isset($_SESSION["is_logged"]) && $_SESSION['is_logged'] && $post->finder == $_SESSION["user"]): ?>
                    <form action="delete_post.php" class="delete-form" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this post? This cannot be undone.');">
                        <input type="hidden" name="post_id" value="<?= $post->id ?>">
                        <button type="submit" class="delete-btn">Remove Post</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach;
} ?>
