<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/trails', name: 'api_trails')]
class TrailController extends AbstractController
{
    private const TRAILS = [
        ['id' => 1, 'name' => 'Forest loop walk',         'level' => 'Beginner', 'min_age' => 5,  'max_group_size' => 999, 'video_url' => 'https://youtu.be/voy6HWI41xI', 'details' => 'Gentle terrain, shaded paths, and a great option for families, recovery days, or short weekend outings.', 'tips' => ['Stay on marked paths', 'Look out for native birds', 'Bring bug repellent'], 'checklist' => ['Comfortable walking shoes', 'Water bottle', 'Camera or smartphone']],
        ['id' => 2, 'name' => 'Nature photography trek',   'level' => 'Easy',     'min_age' => 10, 'max_group_size' => 4,   'video_url' => 'https://youtu.be/TEocbmThGtE', 'details' => 'A slower-paced hike focused on landscape photography, wildlife spotting, and time at scenic stops.',       'tips' => ['Best lighting is early morning or late afternoon', 'Be quiet to not scare wildlife', 'Use a tripod for landscape shots'], 'checklist' => ['Camera', 'Spare batteries', 'Light rain jacket', 'Snacks']],
        ['id' => 3, 'name' => 'Waterfall route',           'level' => 'Easy',     'min_age' => 5,  'max_group_size' => 999, 'video_url' => 'https://youtu.be/63soMeWEBnM', 'details' => 'Shorter hikes that reward the effort with cool rest spots, great for hot weather and group outings.',         'tips' => ['Rocks near the waterfall can be slippery', 'Perfect spot for a picnic'],                                                     'checklist' => ['Waterproof shoes', 'Towel', 'Swimwear', 'Dry bag for electronics']],
        ['id' => 4, 'name' => 'Summit day hike',           'level' => 'Moderate', 'min_age' => 12, 'max_group_size' => 999, 'video_url' => 'https://youtu.be/bcEfI_Vp2w8', 'details' => 'A classic mountain route with elevation gain, scenic viewpoints, and a strong cardio workout.',              'tips' => ['Pace yourself on the ascent', 'Check weather before leaving', 'Start early in the day'],                                     'checklist' => ['Hiking boots', '2L Water minimum', 'First aid kit', 'Sunscreen and hat']],
        ['id' => 5, 'name' => 'Sunrise ridge hike',        'level' => 'Moderate', 'min_age' => 12, 'max_group_size' => 6,   'video_url' => 'https://youtu.be/79lPFjkRY3g', 'details' => 'Early start, cooler temperatures, and excellent visibility. Pack a headlamp and extra layers.',              'tips' => ['Arrive 30 minutes before sunrise', 'Trails can be dark, use a headlamp', 'It will be cold before the sun comes up'],         'checklist' => ['Headlamp with extra batteries', 'Warm layers (fleece/jacket)', 'Thermos with hot tea/coffee']],
        ['id' => 6, 'name' => 'Trail endurance challenge', 'level' => 'Advanced', 'min_age' => 18, 'max_group_size' => 4,   'video_url' => 'https://youtu.be/qc69K9x35Ig', 'details' => 'Longer distance, heavier pack weight, and more demanding terrain for hikers building stamina.',              'tips' => ['Hydrate well the day before', 'Know your turn-around time', 'Inform someone of your route'],                                  'checklist' => ['Trekking poles', 'Navigation tools (Map/GPS)', 'Emergency blanket', 'High-energy food/gels']],
    ];

    #[Route('', name: '')]
    public function index(Request $request): JsonResponse
    {
        $level     = strtolower($request->query->get('level', 'all'));
        $age       = (int) $request->query->get('age', 0);
        $groupSize = (int) $request->query->get('group_size', 0);

        $trails = array_values(array_filter(self::TRAILS, function (array $t) use ($level, $age, $groupSize): bool {
            if ($level !== 'all' && strtolower($t['level']) !== $level) {
                return false;
            }
            if ($age > 0 && $age < $t['min_age']) {
                return false;
            }
            if ($groupSize > 0 && $t['max_group_size'] < $groupSize) {
                return false;
            }
            return true;
        }));

        return $this->json($trails);
    }
}
