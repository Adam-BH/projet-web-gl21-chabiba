<?php
session_start();
require_once __DIR__ . '/../../autoloader.php';

if (!isset($_SESSION['user'])){
    header('Location: /public_html/auth/login.php');
    exit();
}

$userId = $_SESSION['user'];
$siteId = isset($_POST['site_id']) ? (int)$_POST['site_id'] : null;
$start = $_POST['start_date'] ?? null;
$end = $_POST['end_date'] ?? null;
$people = isset($_POST['people']) ? (int)$_POST['people'] : 1;

if (!$siteId || !$start || !$end || $people <= 0){
    http_response_code(400);
    echo 'Missing parameters';
    exit();
}

$startDate = DateTime::createFromFormat('Y-m-d', $start);
$endDate = DateTime::createFromFormat('Y-m-d', $end);
$today = new DateTime('today');

if (!$startDate || !$endDate || $startDate->format('Y-m-d') !== $start || $endDate->format('Y-m-d') !== $end) {
    http_response_code(400);
    echo 'Invalid date format';
    exit();
}

if ($endDate < $startDate) {
    http_response_code(400);
    echo 'Invalid booking dates: end date cannot be before start date';
    exit();
}

if ($startDate < $today || $endDate < $today) {
    http_response_code(400);
    echo 'Booking dates cannot be in the past';
    exit();
}

// ensure the session user maps to a valid users.id
$userRepo = new UserRepository();
$userRow = $userRepo->findById($userId);
// if not found by id, try by username (some flows may store username in session)
if (!$userRow){
    $stmt = ConnexionDB::getInstance()->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->execute([$userId]);
    $userRow = $stmt->fetch(PDO::FETCH_OBJ);
}
if (!$userRow){
    // session user invalid — force login
    header('Location: /public_html/auth/login.php');
    exit();
}

// use the canonical user id from DB to satisfy FK
$canonicalUserId = $userRow->id;

$siteRepo = new CampingSiteRepository();
$site = $siteRepo->findById($siteId);
if (!$site){
    http_response_code(404);
    echo 'Site not found';
    exit();
}

$bookingRepo = new BookingRepository();
$booked = $bookingRepo->getPeopleCountForSiteBetween($siteId, $start, $end);
$available = max(0, ($site->capacity ?? 0) - $booked);

if ($people > $available){
    http_response_code(409);
    echo 'Not enough capacity';
    exit();
}

// create booking using canonical user id
$bookingRepo->create([
    'site_id' => $siteId,
    'user_id' => $canonicalUserId,
    'start_date' => $start,
    'end_date' => $end,
    'people' => $people
]);

header('Location: /public_html/catalogue/details.php?id=' . urlencode($siteId) . '&booked=1');
exit();
