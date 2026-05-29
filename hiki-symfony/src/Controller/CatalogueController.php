<?php

namespace App\Controller;

use App\Entity\CampingSite;
use App\Repository\BookingRepository;
use App\Repository\CampingSiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/catalogue', name: 'app_catalogue')]
class CatalogueController extends AbstractController
{
    #[Route('', name: '')]
    public function index(CampingSiteRepository $repo): Response
    {
        return $this->render('catalogue/index.html.twig', [
            'sites' => $repo->findAll(),
        ]);
    }

    #[Route('/{id}', name: '_show', requirements: ['id' => '\d+'])]
    public function show(CampingSite $site): Response
    {
        return $this->render('catalogue/show.html.twig', ['site' => $site]);
    }

    #[Route('/{id}/book', name: '_book', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function book(
        CampingSite $site,
        Request $request,
        BookingRepository $bookingRepo,
        EntityManagerInterface $em,
    ): Response {
        $start  = $request->request->get('start_date');
        $end    = $request->request->get('end_date');
        $people = max(1, (int) $request->request->get('people', 1));

        $startDate = \DateTimeImmutable::createFromFormat('Y-m-d', $start);
        $endDate   = \DateTimeImmutable::createFromFormat('Y-m-d', $end);
        $today     = new \DateTimeImmutable('today');

        if (!$startDate || !$endDate) {
            return $this->redirectToRoute('app_catalogue_show', ['id' => $site->getId(), 'error' => 'invalid_dates']);
        }

        if ($endDate <= $startDate || $startDate < $today) {
            return $this->redirectToRoute('app_catalogue_show', ['id' => $site->getId(), 'error' => 'invalid_dates']);
        }

        if (!$bookingRepo->isAvailable($site, $startDate, $endDate)) {
            return $this->redirectToRoute('app_catalogue_show', ['id' => $site->getId(), 'error' => 'not_available']);
        }

        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $booking = new \App\Entity\Booking();
        $booking->setSite($site)
                ->setUser($user)
                ->setStartDate($startDate)
                ->setEndDate($endDate)
                ->setPeople($people);

        $em->persist($booking);
        $em->flush();

        return $this->redirectToRoute('app_catalogue_show', ['id' => $site->getId(), 'booked' => 1]);
    }
}
