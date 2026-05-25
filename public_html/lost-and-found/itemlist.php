<?php
require_once __DIR__ . '/../../autoloader.php';

try {
    $postRepository = new PostRepository();
    $posts = $postRepository->findAll();
} catch (Throwable $e) {
    $posts = [];
}

if (!$posts || count($posts) === 0) {
    echo '<p class="empty-state-text">No found items yet. Be the first to post!</p>';
} else {
    foreach ($posts as $post): ?>
        <div class="post-card">
            <?php if (!empty($post->picture)): ?>
                <img src="<?= htmlspecialchars($post->picture) ?>" alt="Item Picture">
            <?php else: ?>
                <img src="/projet-web-gl21-chabiba/assets/Images/placeholder.png" alt="No picture">
            <?php endif; ?>
            <div class="post-content">
                <div class="item">
                    <?= htmlspecialchars(strtoupper($post->item)) ?>
                </div>
                <div class="place">
                    Found in: <?= htmlspecialchars($post->place) ?>
                </div>
                <div class="date">
                    <?= htmlspecialchars(strtoupper($post->created_at)) ?>
                </div>
                <div class="phone">
                    Call to return: <?= htmlspecialchars($post->phone) ?>
                </div>
                <?php if (isset($_SESSION["email"]) && $post->finder == $_SESSION["email"]): ?>
                    <form action="delete_post.php" class="delete-form" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this post? This cannot be undone.');">
                        <input type="hidden" name="post_id" value="<?= $post->id ?>">
                        <button type="submit" class="btn-delete">Remove Post</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach;
} ?>