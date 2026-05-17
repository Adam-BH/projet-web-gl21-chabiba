<?php
require_once '../autoloader.php';

$postRepository = new PostRepository();
$posts = $postRepository->findAll();

// REMOVE THESE TWO LINES once you see that data exists in the dump:
// var_dump($posts); 
// die();

if (!$posts || count($posts) === 0) {
    echo 'No Found Items Here.';
} else {
    foreach ($posts as $post): ?>
        <div class="post-card">
            <?php 
                    $photoPath = "uploads/" . $post->picture; 
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
            </div>
        </div>
    <?php endforeach;
} ?>