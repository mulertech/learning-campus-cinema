<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Seance;
use App\Form\ReservationType;
use App\Repository\FilmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[IsGranted('IS_AUTHENTICATED')]
    #[Route('/reserver/{id}', name: 'app_reserver')]
    public function reserver(Seance $seance, Request $request, EntityManagerInterface $em): Response
    {
        $reservation = new Reservation();

        $form = $this->createForm(ReservationType::class, $reservation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation
                ->setUtilisateur($this->getUser())
                ->setSeance($seance)
                ->setStatut(Reservation::STATUT_CONFIRME);

            $em->persist($reservation);
            $em->flush();

            return $this->redirectToRoute('app_profil');
        }
        return $this->render('cinema/reserver.html.twig', [
            'seance' => $seance,
            'form' => $form->createView(),
        ]);
    }
}
