<?php
session_start();
include_once(__DIR__ . "/../autoloader.php");
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Method not allowed.');
}
$userRepository = new UserRepository();
$postRepository = new PostRepository();

$finder   = $_SESSION['user'];
$item       = trim($_POST['item']       ?? '');
$phone = $userRepository->findById($_SESSION['email'])->phone;
$place    = trim($_POST['place']    ?? '');

$errors = [];


if ($item === '') {
    $errors['title'] = 'Title is required.';
}
$image_path = '';

$has_file = isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE;

if ($has_file) {
    $file  = $_FILES['image'];
    $max   = 5 * 1024 * 1024;

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors['image'] = 'Upload failed (error code ' . $file['error'] . ').';
    }
    elseif ($file['size'] > $max) {
        $errors['image'] = 'Image must be 5 MB or smaller.';
    }
    else {
        $allowed_mime = ['image/jpeg', 'image/png', 'image/webp'];
        $finfo        = new finfo(FILEINFO_MIME_TYPE);
        $mime         = $finfo->file($file['tmp_name']);

        if (!in_array($mime, $allowed_mime, true)) {
            $errors['image'] = 'Only JPG, PNG, and WebP images are allowed.';
        }
    }
}


if (!empty($errors)) {
    $_SESSION['form_errors'] = $errors;
    header('Location: add_post.php');
    exit;
}


if ($has_file) {
    $ext_map   = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    $finfo     = new finfo(FILEINFO_MIME_TYPE);
    $mime      = $finfo->file($_FILES['image']['tmp_name']);
    $ext       = $ext_map[$mime];
    $filename  = date('Ymd_His') . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
    $upload_dir = __DIR__ . '/uploads/';

    // Create uploads/ if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $dest = $upload_dir . $filename;

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
        die('Could not save the uploaded image. Please try again.');
    }

    $image_path = 'uploads/' . $filename;
    $postRepository->create([
        'finder' => $finder,
        'item' =>$item,
        'place' => $place,
        'phone' => $phone,
        'picture' => $image_path
    ]);
    }else {
    $postRepository->create([
        'finder' => $finder,
        'item' =>$item,
        'place' => $place,
        'phone' => $phone
    ]);

}
header('Location: lost&found.php');
exit;