<?php

namespace App\DataFixtures;

use App\Entity\Film;
use App\Entity\Reservation;
use App\Entity\Salle;
use App\Entity\Seance;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
    }
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create('fr_FR');
        $genres = ['Action', 'Comédie', 'Drame', 'Thriller', 'Horreur'];
        $langues = ['VF', 'VOSTFR'];

        $films = [];
        for ($i = 0; $i < 6; $i++) {
            $film = new Film();
            $film
                ->setTitre(ucfirst($faker->word()) . ' ' . ucfirst($faker->word()))
                ->setDuree($faker->numberBetween(60, 180))
                ->setGenre($genres[array_rand($genres)])
                ->setLangue($langues[array_rand($langues)]);

            $films[] = $film;
            $manager->persist($film);
        }

        $salles = [];
        for ($i = 1; $i <= 3; $i++) {
            $salle = new Salle();
            $salle->setNumero($i);
            $salles[] = $salle;

            $manager->persist($salle);
        }

        $seances = [];
        for ($i = 0; $i < 20; $i++) {
            $seance = new Seance();
            $seance
                ->setDate($faker->dateTimeThisMonth())
                ->setSalle($salles[array_rand($salles)])
                ->setFilm($films[array_rand($films)]);
            $seances[] = $seance;

            $manager->persist($seance);
        }

        $utilisateurs = [];
        for ($i = 0; $i < 5; $i++) {
            $utilisateur = new Utilisateur();
            $utilisateur
                ->setEmail($faker->email())
                ->setPassword($this->userPasswordHasher->hashPassword($utilisateur, $faker->password()));
            $utilisateurs[] = $utilisateur;

            $manager->persist($utilisateur);
        }

        for ($i = 0; $i < 100; $i++) {
            $reservation = new Reservation();
            $reservation
                ->setNombrePlaces($faker->numberBetween(1, 3))
                ->setStatut(Reservation::STATUT_CONFIRME)
                ->setSeance($seances[array_rand($seances)])
                ->setUtilisateur($utilisateurs[array_rand($utilisateurs)]);

            $manager->persist($reservation);
        }

        $manager->flush();
    }
}
