<?php

namespace App\Controller;

use App\Entity\Photographe;
use App\Form\PhotographeFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/photographe', name: 'app_photographe_'), IsGranted('ROLE_PHOTOGRAPHE')]
class PhotographeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {

        if (!$this->getUser()->getPhotographe()) {
            return $this->redirectToRoute('app_photographe_profile');
        }

        return $this->render('photographe/index.html.twig', [
            'controller_name' => 'PhotographeController',
        ]);
    }

    #[Route('/create-profile', name: 'photographeprofile')]
    public function profile(Request $request, EntityManagerInterface $entityManager): Response
    {
        $photographe = new Photographe();
        $photographe->setUser($this->getUser());
        $form = $this->createForm(PhotographeFormType::class, $photographe);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($photographe);
            $entityManager->flush();

            return $this->redirectToRoute('app_photographe_index');
            
        }
        return $this->render('photographe/profile-form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
