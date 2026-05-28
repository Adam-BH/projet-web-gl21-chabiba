<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../pages/autoloader.php';

$lat = isset($_GET['lat']) ? floatval($_GET['lat']) : null;
$lon = isset($_GET['lon']) ? floatval($_GET['lon']) : null;
$radiusKm = isset($_GET['radiusKm']) ? floatval($_GET['radiusKm']) : 0;
$start = $_GET['start'] ?? null;
$end = $_GET['end'] ?? null;
$people = isset($_GET['people']) ? (int)$_GET['people'] : null;

$siteRepo = new CampingSiteRepository();
$sites = $siteRepo->findAll();

// helper distance (km)
function haversine($lat1,$lon1,$lat2,$lon2){
    $R=6371.0;
    $dLat=deg2rad($lat2-$lat1);
    $dLon=deg2rad($lon2-$lon1);
    $a=sin($dLat/2)*sin($dLat/2)+cos(deg2rad($lat1))*cos(deg2rad($lat2))*sin($dLon/2)*sin($dLon/2);
    $c=2*asin(min(1,sqrt($a)));
    return $R*$c;
}

$bookingRepo = new BookingRepository();
$out = [];
foreach($sites as $s){
    $sArr = (array)$s;
    $include = true;
    if ($lat !== null && $lon !== null && $radiusKm > 0 && isset($s->lat) && isset($s->lon)){
        $d = haversine($lat,$lon,$s->lat,$s->lon);
        if ($d > $radiusKm) $include = false;
        $sArr['distance_km'] = round($d,2);
    }

    // availability check if dates provided
    if ($include && $start && $end){
        $booked = $bookingRepo->getPeopleCountForSiteBetween($s->id, $start, $end);
        $available = max(0, ($s->capacity ?? 0) - $booked);
        $sArr['available'] = $available;
        if ($people && $people > $available) $include = false;
    } else {
        // if people specified but no dates we can't be sure; still filter by capacity
        if ($people && ($s->capacity ?? 0) < $people) $include = false;
    }

    if ($include) $out[] = $sArr;
}

echo json_encode($out);
