<?php

namespace App\Tests;

use App\Entity\Tarifs;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TarifsTest extends KernelTestCase
{
    // Méthode qui test une éntrée Tarifs
    public function testValidEntry(): void
    {
        // Démarrage du Kernekl de Symfony
        $kernel = self::bootKernel();

            // Récupération du conteneur de services 
            $container = static::getContainer();

            // Créer une nouvelle instance de l'entité Tarifs
            $tarifs  = new Tarifs();

            // Définir le nom de la  Prestation comme 'portrait'
            $tarifs->setPrestations('portrait');

            // Validation de l'entité  
            $errors = $container->get('validator')->validate($tarifs);

            // Vérification du nombre d'erreurs pour qu'il soit égal à zéro
            $this->assertCount(0, $errors);
        
        $this->assertSame('test', $kernel->getEnvironment());

    }



        // Méthode qui test une éntrée avec un nom invalide
        public function testInvalidEntry(): void
        { 
            // Démarrage du Kernekl de Symfony
            self::bootKernel();

            // Récupération du conteneur de services 
            $container = static::getContainer();

            // Créer une nouvelle instance de l'entité Tarifs
            $tarifs  = new Tarifs;

            // Définir le nom de la  Prestation comme une chaîne vide
            $tarifs->setPrestations('');

            // Validation de l'entité 
            $errors = $container->get('validator')->validate($tarifs);

            // Vérification du nombre d'erreurs pour qu'il soit égal à un
            $this->assertCount(0, $errors);
        }
    }



            // $routerService = static::getContainer()->get('router');
        // $myCustomService = static::getContainer()->get(CustomService::class);