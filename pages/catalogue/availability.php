<?php
require_once __DIR__ . '/../autoloader.php';
header('Content-Type: application/json');

$siteId = isset($_GET['site_id']) ? (int)$_GET['site_id'] : null;
$start = $_GET['start_date'] ?? null;
$end = $_GET['end_date'] ?? null;

if (!$siteId || !$start || !$end) {
    http_response_code(400);
    echo json_encode(['error' => 'missing_parameters']);
    exit;
}

$siteRepo = new CampingSiteRepository();
$site = $siteRepo->findById($siteId);
if (!$site) {
    http_response_code(404);
    echo json_encode(['error' => 'site_not_found']);
    exit;
}

$bookingRepo = new BookingRepository();
$booked = $bookingRepo->getPeopleCountForSiteBetween($siteId, $start, $end);
$available = max(0, ($site->capacity ?? 0) - $booked);

echo json_encode(['site_id' => $siteId, 'capacity' => (int)$site->capacity, 'booked' => $booked, 'available' => $available]);
