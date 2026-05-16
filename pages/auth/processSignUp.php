<?php
session_start();
require_once('../autoloader.php');
$userRepository = new UserRepository();
$email = $_POST['email'];
$user = $_POST['username'];
$pwd = $_POST['password'];
$pwd2 = $_POST['password2'];
$testing = $userRepository->findById($email);
if (isset($email) && isset($pwd) && isset($user) && isset($pwd2) && $pwd == $pwd2) {
    if ($testing == false) {
        $_SESSION['user'] = $email;
        $userRepository->create([
            'username' => $user,
            'email' => $email,
            'password' => $pwd
        ]);
        header('location:search.php');
    } else {
        header('location:signup.php?error=existant_account');
        exit();
    }
} else {
    header('location:signup.php?error=invalid_credentials');
    exit();
}