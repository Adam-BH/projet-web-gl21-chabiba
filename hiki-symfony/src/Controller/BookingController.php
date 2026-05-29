<?php

namespace App\Controller;

use App\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/bookings', name: 'app_bookings')]
#[IsGranted('ROLE_USER')]
class BookingController extends AbstractController
{
    #[Route('', name: '')]
    public function index(BookingRepository $repo): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $bookings = $repo->findBy(['user' => $user], ['createdAt' => 'DESC']);

        return $this->render('bookings/index.html.twig', ['bookings' => $bookings]);
    }
}
