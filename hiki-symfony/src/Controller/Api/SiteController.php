<?php

namespace App\Controller\Api;

use App\Repository\CampingSiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/sites', name: 'api_sites')]
class SiteController extends AbstractController
{
    #[Route('', name: '')]
    public function index(CampingSiteRepository $repo): JsonResponse
    {
        $sites = $repo->findAll();

        $data = array_map(fn ($s) => [
            'id'          => $s->getId(),
            'name'        => $s->getName(),
            'city'        => $s->getCity(),
            'capacity'    => $s->getCapacity(),
            'description' => $s->getDescription(),
            'lat'         => $s->getLat(),
            'lon'         => $s->getLon(),
        ], $sites);

        return $this->json($data);
    }
}
