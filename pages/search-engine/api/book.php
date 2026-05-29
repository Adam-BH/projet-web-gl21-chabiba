<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../pages/autoloader.php';

// expects POST: site_id, start, end, people
if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
    http_response_code(405);
    echo json_encode(['error'=>'Method not allowed']);
    exit();
}

$siteId = isset($_POST['site_id']) ? (int)$_POST['site_id'] : null;
$start = $_POST['start'] ?? null;
$end = $_POST['end'] ?? null;
$people = isset($_POST['people']) ? (int)$_POST['people'] : 1;

if (!isset($_SESSION['user'])){
    echo json_encode(['needLogin'=>true]);
    exit();
}

$userId = $_SESSION['user'];

if (!$siteId || !$start || !$end || $people <= 0){
    http_response_code(400);
    echo json_encode(['error'=>'Missing parameters']);
    exit();
}

$userRepo = new UserRepository();
$userRow = $userRepo->findById($userId);
if (!$userRow){
    // try by username
    $stmt = ConnexionDB::getInstance()->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->execute([$userId]);
    $userRow = $stmt->fetch(PDO::FETCH_OBJ);
}
if (!$userRow){
    echo json_encode(['needLogin'=>true]);
    exit();
}
$canonicalUserId = $userRow->id;

$siteRepo = new CampingSiteRepository();
$site = $siteRepo->findById($siteId);
if (!$site){ http_response_code(404); echo json_encode(['error'=>'Site not found']); exit(); }

$bookingRepo = new BookingRepository();
$booked = $bookingRepo->getPeopleCountForSiteBetween($siteId, $start, $end);
$available = max(0, ($site->capacity ?? 0) - $booked);
if ($people > $available){ http_response_code(409); echo json_encode(['error'=>'Not enough capacity']); exit(); }

$bookingRepo->create([
    'site_id'=>$siteId,
    'user_id'=>$canonicalUserId,
    'start_date'=>$start,
    'end_date'=>$end,
    'people'=>$people
]);

echo json_encode(['success'=>true]);
