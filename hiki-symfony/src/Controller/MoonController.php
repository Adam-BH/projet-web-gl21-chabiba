<?php

namespace App\Controller;

use App\Service\MoonService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/moon', name: 'app_moon')]
class MoonController extends AbstractController
{
    #[Route('', name: '')]
    public function index(Request $request, MoonService $moon): Response
    {
        $week          = $moon->getWeek();
        $selectedIndex = max(0, min((int) $request->query->get('d', 0), count($week) - 1));

        return $this->render('moon/index.html.twig', [
            'week'          => $week,
            'selected'      => $week[$selectedIndex],
            'selectedIndex' => $selectedIndex,
        ]);
    }
}
