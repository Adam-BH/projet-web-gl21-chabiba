<?php
session_start();
require_once '../autoloader.php';
$userRepository=new UserRepository();
$email=$_POST['email'];
$user=$userRepository->findById($email);
$pwd=$_POST['password'];
$db_pwd=$user->password;
if(isset($db_pwd) && (password_verify($pwd, $db_pwd))){
    $_SESSION['user']=$email;
    header('location:search.php');
} else {
    header('location:login.php?error=invalid_credentials');
    exit();
}