<?php

namespace App\Controller;

use App\Entity\Modele;
use App\Form\ModeleFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/modele', name: 'app_modele_'), IsGranted('ROLE_MODELE')]
class ModeleController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        if (!$this->getUser()->getModele()) {
            return $this->redirectToRoute('app_modele_profile');
        }

        return $this->render('modele/index.html.twig', [
            'controller_name' => 'ModeleController',
        ]);
    }

    #[Route('/create-profile', name: 'profile')]
    public function profile(Request $request, EntityManagerInterface $entityManager): Response
    {
        $modele = new Modele();
        $modele->setUser($this->getUser());
        $form = $this->createForm(ModeleFormType::class, $modele);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($modele);
            $entityManager->flush();
        }
        return $this->render('modele/profile-form.html.twig', [
            'form' => $form,
        ]);
    } 
}
