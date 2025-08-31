<?php

namespace App\Controller\Admin;

use App\Entity\Film;
use App\Entity\Reservation;
use App\Entity\Salle;
use App\Entity\Seance;
use App\Entity\Utilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->redirectToRoute('admin_film_index');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Html');
    }

    public function configureMenuItems(): iterable
    {
         yield MenuItem::linkToCrud('Films', 'fas fa-film', Film::class);
         yield MenuItem::linkToCrud('Salles', 'fas fa-door-open', Salle::class);
         yield MenuItem::linkToCrud('Séances', 'fas fa-calendar', Seance::class);
         yield MenuItem::linkToCrud('Réservations', 'fas fa-ticket-alt', Reservation::class);
         yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', Utilisateur::class);
    }
}
