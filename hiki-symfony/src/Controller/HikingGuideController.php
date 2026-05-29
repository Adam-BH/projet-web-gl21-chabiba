<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HikingGuideController extends AbstractController
{
    #[Route('/hiking-guide', name: 'app_hiking_guide')]
    public function index(): Response
    {
        return $this->render('hiking_guide/index.html.twig');
    }
}
