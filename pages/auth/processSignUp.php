<?php
session_start();
require_once('../autoloader.php');
$userRepository = new UserRepository();
$adrRepository = new AdressRepository();
$email = $_POST['email'];
$user = $_POST['username'];
$pwd = $_POST['password'];
$pwd2 = $_POST['password2'];
$phone = $_POST['phone'];
$testing = $userRepository->findById($email);
function getClientIP()
{
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return trim($ip);
}

$ip = getClientIP();

// If running locally, skip geolocation
if ($ip === '127.0.0.1' || $ip === '::1') {
    $city = 'Localhost';
    $lat  = 0;
    $lon  = 0;
} else {
    $response = file_get_contents("http://ip-api.com/json/{$ip}");
    $data = json_decode($response, true);

    if ($data['status'] === 'success') {
        $city = $data['city'];
        $lat  = $data['lat'];
        $lon  = $data['lon'];
    } else {
        $city = 'Unknown';
        $lat  = 0;
        $lon  = 0;
    }
}
$findLocation = $adrRepository->findById($city);
if ($pwd == $pwd2) {
    if ($testing == false) {
        $_SESSION['user'] = $user;
        $userRepository->create([
            'username' => $user,
            'id' => $email,
            'password' => password_hash($pwd, PASSWORD_DEFAULT),
            'phone' => $phone,
            'city' => $city
        ]);
        if ($findLocation == false && $city != 'unknown' && $city != 'localhost') {
            $adrRepository->create([
                'id' => $city,
                'lat' => $lat,
                'lon' => $lon
            ]);
        }
        $_SESSION['is_logged']=true;
        header('location:../../');
    } else {
        header('location:signup.php?error=existant_account');
        exit();
    }
} else {
    header('location:signup.php?error=invalid_credentials');
    exit();
}