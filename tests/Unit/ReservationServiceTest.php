<?php

namespace App\Tests\Unit;

use App\Entity\Salle;
use App\Entity\Seance;
use App\Service\ReservationService;
use PHPUnit\Framework\TestCase;

class ReservationServiceTest extends TestCase
{
    public function testPeutReserverSiPlacesDisponibles()
    {
        $salle = new Salle();
        $salle->setCapacite(50);

        $seance = new Seance();
        $seance->setSalle($salle);

        $service = new ReservationService();
        $this->assertTrue($service->peutReserver($seance));
    }

    public function testNePeutReserverSiComplet()
    {
        $salle = new Salle();
        $salle->setCapacite(0);

        $seance = new Seance();
        $seance->setSalle($salle);

        $service = new ReservationService();
        $this->assertFalse($service->peutReserver($seance));
    }
}
