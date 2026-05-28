<?php
require_once __DIR__ . '/../pages/autoloader.php';

$level = $_GET['level'] ?? 'all';
$age = $_GET['age'] ?? '';
$group_size = $_GET['group_size'] ?? '';

try {
    $trailRepo = new TrailRepository();
    $trails = $trailRepo->findWithFilters($level, $age, $group_size);
} catch (Throwable $e) {
    // If table does not exist or error, fallback to empty array
    $trails = [];
}

// Dummy data including new fields like video_url, tips, and checklist
if (empty($trails)) {
    $allTrails = [
        [
            'id' => 1,
            'name' => 'Forest loop walk',
            'level' => 'Beginner',
            'min_age' => 5,
            'max_group_size' => 999, // Any
            'video_url' => 'https://youtu.be/voy6HWI41xI', // Placeholder Youtube URL
            'details' => 'Gentle terrain, shaded paths, and a great option for families, recovery days, or short weekend outings.',
            'tips' => '["Stay on marked paths", "Look out for native birds", "Bring bug repellent"]',
            'checklist' => '["Comfortable walking shoes", "Water bottle", "Camera or smartphone"]'
        ],
        [
            'id' => 2,
            'name' => 'Nature photography trek',
            'level' => 'Easy',
            'min_age' => 10,
            'max_group_size' => 4,
            'video_url' => 'https://youtu.be/TEocbmThGtE',
            'details' => 'A slower-paced hike focused on landscape photography, wildlife spotting, and time at scenic stops.',
            'tips' => '["Best lighting is early morning or late afternoon", "Be quiet to not scare wildlife", "Use a tripod for landscape shots"]',
            'checklist' => '["Camera", "Spare batteries", "Light rain jacket", "Snacks"]'
        ],
        [
            'id' => 3,
            'name' => 'Waterfall route',
            'level' => 'Easy',
            'min_age' => 5,
            'max_group_size' => 999,
            'video_url' => 'https://youtu.be/63soMeWEBnM',
            'details' => 'Shorter hikes that reward the effort with cool rest spots, great for hot weather and group outings.',
            'tips' => '["Rocks near the waterfall can be slippery", "Perfect spot for a picnic"]',
            'checklist' => '["Waterproof shoes", "Towel", "Swimwear", "Dry bag for electronics"]'
        ],
        [
            'id' => 4,
            'name' => 'Summit day hike',
            'level' => 'Moderate',
            'min_age' => 12,
            'max_group_size' => 999,
            'video_url' => 'https://youtu.be/bcEfI_Vp2w8',
            'details' => 'A classic mountain route with elevation gain, scenic viewpoints, and a strong cardio workout.',
            'tips' => '["Pace yourself on the ascent", "Check weather before leaving", "Start early in the day"]',
            'checklist' => '["Hiking boots", "2L Water minimum", "First aid kit", "Sunscreen and hat"]'
        ],
        [
            'id' => 5,
            'name' => 'Sunrise ridge hike',
            'level' => 'Moderate',
            'min_age' => 12,
            'max_group_size' => 6,
            'video_url' => 'https://youtu.be/79lPFjkRY3g',
            'details' => 'Early start, cooler temperatures, and excellent visibility. Pack a headlamp and extra layers.',
            'tips' => '["Arrive 30 minutes before sunrise", "Trails can be dark, use a headlamp", "It will be cold before the sun comes up"]',
            'checklist' => '["Headlamp with extra batteries", "Warm layers (fleece/jacket)", "Thermos with hot tea/coffee"]'
        ],
        [
            'id' => 6,
            'name' => 'Trail endurance challenge',
            'level' => 'Advanced',
            'min_age' => 18,
            'max_group_size' => 4,
            'video_url' => 'https://youtu.be/qc69K9x35Ig',
            'details' => 'Longer distance, heavier pack weight, and more demanding terrain for hikers building stamina.',
            'tips' => '["Hydrate well the day before", "Know your turn-around time", "Inform someone of your route"]',
            'checklist' => '["Trekking poles", "Navigation tools (Map/GPS)", "Emergency blanket", "High-energy food/gels"]'
        ]
    ];

    $trails = array_filter($allTrails, function ($t) use ($level, $age, $group_size) {
        $matchLevel = ($level === 'all' || strtolower($t['level']) === strtolower($level));
        $matchAge = empty($age) || ($age >= $t['min_age']);
        $matchGroup = empty($group_size) || ($t['max_group_size'] >= $group_size);
        return $matchLevel && $matchAge && $matchGroup;
    });
    $trails = array_values($trails);
}

header('Content-Type: application/json');
echo json_encode($trails);
