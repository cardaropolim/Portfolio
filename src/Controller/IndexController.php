<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('index/index.html.twig');

        // Récupérer le modèle de l'utilisateur connecté
        $modele = $this->getUser()->getModele();
    
        // Vérifier si le modèle existe
        if (!$modele) {
            // Rediriger vers la page de création de profil de modèle s'il n'existe pas
            return $this->redirectToRoute('app_modele_modeleprofile');
        }
    
        return $this->render('modele/index.html.twig', [
            'modele' => $modele,
            'controller_name' => 'ModeleController',
        ]);
    }
}

