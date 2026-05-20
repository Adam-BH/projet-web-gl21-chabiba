<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../pages/autoloader.php';

$repo = new CampingSiteRepository();
$sites = $repo->findAll();

echo json_encode($sites);
