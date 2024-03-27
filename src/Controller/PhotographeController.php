<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/photographe', name: 'app_photographe_'), IsGranted('ROLE_PHOTOGRAPHE')]
class PhotographeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('photographe/index.html.twig', [
            'controller_name' => 'PhotographeController',
        ]);
    }
}
