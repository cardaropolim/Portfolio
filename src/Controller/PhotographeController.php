<?php

namespace App\Controller;

use App\Entity\Photographe;
use App\Entity\Tarifs;
use App\Form\PhotographeFormType;
use App\Form\TarifType;
use App\Repository\PhotographeRepository;
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
            return $this->redirectToRoute('app_photographe_photographe_profile');
        }

        return $this->render('photographe/index.html.twig', [
            'controller_name' => 'PhotographeController',
        ]);
    }

    #[Route('/informations-profile', name: 'photographe_profile')]
    public function profile(Request $request, EntityManagerInterface $entityManager): Response
    {

        $photographe = new Photographe();
        if ($this->getUser()->getPhotographe()) {
            $photographe = $this->getUser()->getPhotographe();
        }

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


    #[Route('/book_photographe', name: 'book_photographe')]

    public function book_modele(): Response
    {
        return $this->render('photographe/book-photographe.html.twig', []);
    }

    #[Route('/tarifs', name: 'tarifs_form')]
    public function tarifs_form(Request $request, EntityManagerInterface $entityManager, PhotographeRepository $photographe): Response
    {
        $tarifsUser = $this->getUser()->getTarifs();

        $tarif = new Tarifs();
        // Créer le formulaire en utilisant le type de formulaire TarifsType et l'entité Modele récupérée
        $form = $this->createForm(TarifType::class, $tarif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tarif->setUser($this->getUser());
            $entityManager->persist($tarif);
            $entityManager->flush();

            // Rediriger vers la page d'accueil ou toute autre page appropriée
            return $this->redirectToRoute('app_photographe_index');
        }

        return $this->render('photographe/tarifs.html.twig', [
            'form' => $form->createView(),
            'tarifsUser' => $tarifsUser
        ]);
    }
}
