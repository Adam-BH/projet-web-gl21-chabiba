<?php
session_start();
include_once("../autoloader.php");
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method not allowed.');
}

if (!isset($_SESSION['user'])) {
    http_response_code(401);
    die('You must be logged in to delete a post.');
}

$postRepository = new PostRepository();
$post_id = isset($_POST['post_id']) ? $_POST['post_id'] : 0;

if ($post_id <= 0) {
    die('Invalid post ID.');
}
$post = $postRepository->findById($post_id);

if (!$post) {
    http_response_code(404);
    die('Post not found.');
}

if ($_SESSION['user'] !==  $post->finder) {
    http_response_code(403);
    die('You are not authorized to delete this post.');
}
$image_path = $postRepository->findById($post_id)->picture;
if (file_exists($image_path)) {
    unlink($image_path);
}
$postRepository->delete($post_id);
header('Location: lost&found.php?deleted=1');
exit;