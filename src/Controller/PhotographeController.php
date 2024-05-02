<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\Photographe;
use App\Entity\Tarifs;
use App\Form\MediaType;
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

    #[Route('/compte', name: 'compte')]
    public function compte(): Response
    {
        return $this->render('photographe/compte.html.twig', []);
    }

    // not in used for now //
    #[Route('/contacts', name: 'contacts')]
    public function contacts(): Response
    {
        return $this->render('photographe/contacts.html.twig', []);
    }


    #[Route('/FAQ', name: 'FAQ')]
    public function FAQ(): Response
    {
        return $this->render('photographe/FAQ.html.twig', []);
    }

    // route book photographe //
    #[Route('/book_photographe', name: 'book_photographe')]

    public function book_modele(): Response
    {
        // Récupère les données de l'utilisateur
        $user = $this->getUser(); 

        return $this->render('photographe/book-photographe.html.twig', [
            'user' => $user,
        ]);
    }

    // fonction pour fill prestations/tarifs //
    #[Route('/tarifs', name: 'tarifs_form')]
    public function tarifs_form(Request $request, EntityManagerInterface $entityManager, PhotographeRepository $photographe): Response
    {
        $tarifsUser = $this->getUser()->getTarifs();

        $tarif = new Tarifs();
        // Créer le formulaire en utilisant le type de formulaire TarifsType et l'entité Photographe récupérée
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

    #[Route('/deleteTarifs', name: 'delete_tarifs')]
    public function deleteTarif(Request $request, EntityManagerInterface $entityManager, Tarifs $tarif): Response
    {
        // Vérifie si le token CSRF est valide
        if ($this->isCsrfTokenValid('delete' . $tarif->getId(), $request->request->get('_token'))) {
            // Supprime le tarif de la base de données
            $entityManager->remove($tarif);
            $entityManager->flush();

            $this->addFlash('success', 'Tarif supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide.');
        }

        return $this->redirectToRoute('app_photographe_index');
    }

    #[Route('/editTarif/{id}', name: 'edit_tarif')]
    public function editTarif(Request $request, EntityManagerInterface $entityManager, Tarifs $tarif): Response
    {
        // Récupérer le tarif à partir de la base de données
        $tarif = $entityManager->getRepository(Tarifs::class)->find($tarif->getId());

        // Créer le formulaire en utilisant le type de formulaire TarifsType et le tarif récupéré
        $form = $this->createForm(TarifType::class, $tarif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // Rediriger vers la page d'accueil ou toute autre page appropriée
            return $this->redirectToRoute('app_photographe_index');
        }

        return $this->render('photographe/edit_tarif.html.twig', [
            'form' => $form->createView(),
            'tarif' => $tarif,
        ]);
    }

    #[Route('/media/create', name: 'media_create')]
    public function media_create(Request $request, EntityManagerInterface $manager): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Vérifier si l'utilisateur est un modèle et récupérer son modèle
        $photographe = $user->getPhotographe();

        // Vérifier si le modèle existe
        if (!$photographe) {
            // Rediriger vers la page de création de profil de modèle s'il n'existe pas
            return $this->redirectToRoute('app_photographe_photographe_profile');
        }
            $medias = $this->getUser()->getMedia();

            $sections = [];
            foreach ($medias as $media) {
                $sections[] = $media->getDestination();
        }

        $media = new Media();

        $form = $this->createForm(MediaType::class, $media);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($sections as $section) {
                //dd($section, $media->getDestination());
                if ($form->isSubmitted() && $form->isValid()) {
                    foreach ($sections as $section) {
                        if ($media->getDestination() === $section) {
                            $this->addFlash('danger', 'Section déjà utilisée');
                            return $this->render('photographe/media_create.html.twig', [
                                'form' => $form->createView(),
                                'photographe' => $photographe,
                            ]);
                        }
                    }
                }
            }

            $files = $form->get('nom')->getData();
            $nbMedias = count($user->getMedia());

            $file_name = date('Y-d-d-H-i-s') . '-' . $media->getNom() . ($nbMedias + 1) . '.' . $files->getClientOriginalExtension();

            $files->move($this->getParameter('upload_dir'), $file_name);

            // $media->setNom($media->getNom() . ($nbMedias));
            
            $media->setNom($file_name);
            $manager->persist($media);
            $user->addMedium($media);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Média créé');
            return $this->redirectToRoute('app_photographe_media_create');
        }

        return $this->render('photographe/media_create.html.twig', [
            'form' => $form->createView(),
            'photographe' => $photographe,
        ]); 
    }


    #[Route('/media/{id}/delete', name: 'media_delete')]
    public function media_delete(Media $media, Request $request, EntityManagerInterface $manager): Response
    {
        // Vérifier si l'utilisateur connecté est le propriétaire du média
        $user = $this->getUser();
        if ($user !== $media->getUser()) {
            throw new AccessDeniedException("Vous n'avez pas la permission de supprimer ce média.");
        }

        // Supprimer le média de la base de données et du système de fichiers
        $manager->remove($media);
        $manager->flush();

        // Rediriger vers la page de création de média avec un message flash
        $this->addFlash('success', 'Le média a été supprimé avec succès.');
        return $this->redirectToRoute('app_photographe_media_create');
    }

    #[Route('/media/list', name: 'media_list')]
    public function media_list(Request $request): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

                // Vérifier si l'utilisateur est un modèle et récupérer son modèle
    $modele = $user->getPhotographe();

        // Récupérer les médias de l'utilisateur
        $medias = $user->getMedia();

        return $this->render('photographe/media_list.html.twig', [
            'user' => $user,
            'medias' => $medias,
            // 'photographe' => $photographe,
        ]);
    }
}
