<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\FilmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class CinemaController extends AbstractController
{
    #[Route('/programmation', name: 'app_programmation')]
    public function programmation(FilmRepository $filmRepository): Response
    {
        $films = $filmRepository->findAll();
        return $this->render('cinema/programmation.html.twig', [
            'films' => $films,
        ]);
    }

    #[IsGranted('IS_AUTHENTICATED')]
    #[Route('/profil', name: 'app_profil')]
    public function profil(): Response
    {
        return $this->render('cinema/reservations.html.twig');
    }

    #[IsGranted('IS_AUTHENTICATED')]
    #[Route('/annuler-reservation/{id}', name: 'app_annuler_reservation')]
    public function annulerReservation(Reservation $reservation, EntityManagerInterface $em): Response
    {
        if ($reservation->getUtilisateur()->getId() === $this->getUser()->getId()) {
            $reservation->setStatut(Reservation::STATUT_ANNULE);
            $em->flush();
        }
        return $this->redirectToRoute('app_profil');
    }
}
