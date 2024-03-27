<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/modele', name: 'app_modele_'), IsGranted('ROLE_MODELE')]
class ModeleController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('modele/index.html.twig', [
            'controller_name' => 'ModeleController',
        ]);
    }
}
