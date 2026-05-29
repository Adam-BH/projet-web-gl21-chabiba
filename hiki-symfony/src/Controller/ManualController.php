<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/manual', name: 'app_manual')]
class ManualController extends AbstractController
{
    #[Route('', name: '')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_manual_hiking');
    }

    #[Route('/hiking', name: '_hiking')]
    public function hiking(): Response
    {
        return $this->render('manual/hiking.html.twig');
    }

    #[Route('/fire', name: '_fire')]
    public function fire(): Response
    {
        return $this->render('manual/fire.html.twig');
    }

    #[Route('/tent', name: '_tent')]
    public function tent(): Response
    {
        return $this->render('manual/tent.html.twig');
    }
}
