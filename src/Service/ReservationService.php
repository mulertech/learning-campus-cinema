<?php

namespace App\Service;

use App\Entity\Reservation;
use App\Entity\Seance;

class ReservationService
{
    public function placesRestantes(Seance $seance): int
    {
        $nbPlacesReservees = 0;
        foreach ($seance->getReservations() as $reservation) {
            if ($reservation->getStatut() === Reservation::STATUT_CONFIRME) {
                $nbPlacesReservees += $reservation->getNombrePlaces();
            }
        }
        return $seance->getSalle()->getCapacite() - $nbPlacesReservees;
    }

    public function peutReserver(Seance $seance): bool
    {
        return $this->placesRestantes($seance) > 0;
    }
}
