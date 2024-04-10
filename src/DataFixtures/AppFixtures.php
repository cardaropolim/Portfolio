<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        // // create user Admin
        // $admin = new User();
        // $admin->setEmail('admin@gmail.com');
        // $admin->setRoles(['ROLE_ADMIN']);
        // $admin->setPassword($this->hasher->hashPassword($admin, 'password'));

        // $manager->persist($admin);

        // create 5 user modele
        for ($i = 0; $i < 5; $i++) {
            $modele = new User();
            $modele->setEmail('modele' . ($i + 1) . '@gmail.com');
            $modele->setRoles(['ROLE_MODELE']);
            $modele->setPassword($this->hasher->hashPassword($modele, 'password'));

            $manager->persist($modele);
        }

        // create 10 user photographe
        for ($i = 0; $i < 10; $i++) {
            $photographe = new User();
            $photographe->setEmail('photographe' . ($i + 1) . '@gmail.com');
            $photographe->setRoles(['ROLE_PHOTOGRAPHE']);
            $photographe->setPassword($this->hasher->hashPassword($photographe, 'password'));

            $manager->persist($photographe);
        }

        $manager->flush();
    }
}