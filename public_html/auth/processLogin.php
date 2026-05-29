<?php
session_set_cookie_params(0);
session_start();
require_once __DIR__ . '/../../autoloader.php';
$userRepository=new UserRepository();
$email=$_POST['email'];
$user=$userRepository->findById($email);
$pwd=$_POST['password'];
$db_pwd=$user->password;
if(isset($db_pwd) && (password_verify($pwd, $db_pwd))){
    $_SESSION['user']=$user->username;
    $_SESSION['email']=$email;
    $_SESSION['is_logged']=true;
    header('location:/projet-web-gl21-chabiba/public_html/index.php');
} else {
    header('location:login.php?error=invalid_credentials');
    exit();
}