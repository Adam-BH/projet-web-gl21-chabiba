<?php

namespace App\Controller\Api;

use App\Repository\BookingRepository;
use App\Repository\CampingSiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/search', name: 'api_search')]
class SearchController extends AbstractController
{
    #[Route('', name: '')]
    public function search(Request $request, CampingSiteRepository $siteRepo, BookingRepository $bookingRepo): JsonResponse
    {
        $lat      = $request->query->get('lat')    !== null ? (float) $request->query->get('lat')    : null;
        $lon      = $request->query->get('lon')    !== null ? (float) $request->query->get('lon')    : null;
        $radius   = $request->query->get('radiusKm') ? (float) $request->query->get('radiusKm') : 0;
        $start    = $request->query->get('start');
        $end      = $request->query->get('end');
        $people   = $request->query->get('people') ? (int) $request->query->get('people') : null;

        $startDate = $start ? \DateTimeImmutable::createFromFormat('Y-m-d', $start) : null;
        $endDate   = $end   ? \DateTimeImmutable::createFromFormat('Y-m-d', $end)   : null;

        $sites = $siteRepo->findAll();
        $result = [];

        foreach ($sites as $site) {
            $row = [
                'id'          => $site->getId(),
                'name'        => $site->getName(),
                'city'        => $site->getCity(),
                'capacity'    => $site->getCapacity(),
                'description' => $site->getDescription(),
                'lat'         => $site->getLat(),
                'lon'         => $site->getLon(),
            ];

            // Geolocation filter
            if ($lat !== null && $lon !== null && $radius > 0 && $site->getLat() && $site->getLon()) {
                $d = $this->haversine($lat, $lon, $site->getLat(), $site->getLon());
                if ($d > $radius) {
                    continue;
                }
                $row['distance_km'] = round($d, 2);
            }
            
            // Availability filter
            if ($startDate && $endDate) {
                $available = $bookingRepo->isAvailable($site, $startDate, $endDate);
                $row['available'] = $available;
                if ($people && !$available) {
                    continue;
                }
            } elseif ($people && $site->getCapacity() < $people) {
                continue;
            }

            $result[] = $row;
        }

        return $this->json($result);
    }

    private function haversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $R    = 6371.0;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a    = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;
        return $R * 2 * asin(min(1, sqrt($a)));
    }
}
