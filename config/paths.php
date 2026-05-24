<?php
define('PROJECT_ROOT', '/projet-web-gl21-chabiba');
define('SRC_DIR', __DIR__ . '/../src');
define('CLASSES_DIR', SRC_DIR . '/Classes');
define('INCLUDES_DIR', SRC_DIR . '/Includes');

const ROUTES = [
    'home' => PROJECT_ROOT . '/public_html/index.php',
    'weather' => PROJECT_ROOT . '/public_html/weather.php',
    'hiking_guide' => PROJECT_ROOT . '/public_html/hiking-guide.php',
    'equipment' => PROJECT_ROOT . '/public_html/equipment.php',
    'moon' => PROJECT_ROOT . '/public_html/moon.php',
    'community' => PROJECT_ROOT . '/public_html/community.php',
    'catalogue' => PROJECT_ROOT . '/public_html/catalogue/index.php',
    'bookings' => PROJECT_ROOT . '/public_html/bookings.php',
    'lost_found' => PROJECT_ROOT . '/public_html/lost-and-found/lost-and-found.php',
    'login' => PROJECT_ROOT . '/public_html/auth/login.php',
    'signup' => PROJECT_ROOT . '/public_html/auth/signup.php',
    'logout' => PROJECT_ROOT . '/public_html/auth/logout.php',
];
