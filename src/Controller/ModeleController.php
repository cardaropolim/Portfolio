<?php

namespace App\Controller;


use App\Entity\Media;
use App\Entity\Modele;
use App\Entity\Tarifs;

use App\Form\MediaType;
use App\Form\ModeleFormType;
use App\Form\TarifType;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/modele', name: 'app_modele_'), IsGranted('ROLE_MODELE')]
class ModeleController extends AbstractController
{
        #[Route('/', name: 'index')]
        public function index(): Response
        {
            if (!$this->getUser()->getModele()) {
                return $this->redirectToRoute('app_modele_modeleprofile');
            }

            return $this->render('modele/index.html.twig', [
                'controller_name' => 'ModeleController',
            ]);
        }


        // fonction pour fill, récupérer, modifier informations modèle //
        #[Route('/informations-profile', name: 'modeleprofile')]
        public function profile(Request $request, EntityManagerInterface $entityManager): Response
        {
            $modele = new Modele();
            if ($this->getUser()->getModele()) {
                $modele = $this->getUser()->getModele();
            }
            $modele->setUser($this->getUser());
            $form = $this->createForm(ModeleFormType::class, $modele);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($modele);
                $entityManager->flush();

                return $this->redirectToRoute('app_modele_index');
            }
            return $this->render('modele/profile-form.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        // not in used for now //

    #[Route('/contacts', name: 'contacts')]
    public function contacts(): Response
    {
        return $this->render('modele/contacts.html.twig', []);
    }
    
    #[Route('/FAQ', name: 'FAQ')]
    public function FAQ(): Response
    {
        return $this->render('modele/FAQ.html.twig', []);
    }

    #[Route('/bibliotheque', name: 'bibliotheque')]
    public function bibliotheque(): Response
    {
        // Récupère les données de l'utilisateur   
        $user = $this->getUser(); 

        return $this->render('modele/bibliotheque.html.twig', [
        'user' => $user,
        ]);
    }


    // route book modele //
    #[Route('/book_modele', name: 'book_modele')]
    public function book_modele(): Response
    {

         // Récupère les données de l'utilisateur   
        $user = $this->getUser(); 

        return $this->render('modele/book-modele.html.twig', [
        'user' => $user,
        ]);
    }

    // fonction pour fill prestations/tarifs //
    #[Route('/tarifs', name: 'tarifs_form')]
    public function tarifs_form(Request $request, EntityManagerInterface $entityManager): Response
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
            return $this->redirectToRoute('app_modele_index');
        }

        return $this->render('modele/tarifs.html.twig', [
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

        return $this->redirectToRoute('app_modele_index');
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
            return $this->redirectToRoute('app_modele_index');
        }

        return $this->render('modele/edit_tarif.html.twig', [
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
        $modele = $user->getModele();

        // Vérifier si le modèle existe
        if (!$modele) {
            // Rediriger vers la page de création de profil de modèle s'il n'existe pas
            return $this->redirectToRoute('app_modele_modeleprofile');
        }

        $medias = $this->getUser()->getMedia();

        $sections = [];
        foreach ($user->getMedia() as $media) {
            $sections[] = $media->getDestination();
        }
    
        // Créer un nouveau média
        $media = new Media();
    
        // Créer le formulaire
        $form = $this->createForm(MediaType::class, $media);
        $form->handleRequest($request);
    
        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
        // Vérifier si la section est déjà utilisée
        if ($media->getDestination() !== 'bibliotheque' && in_array($media->getDestination(), $sections)) {
            $this->addFlash('danger', 'Section déjà utilisée');
            return $this->redirectToRoute('app_modele_media_create', [
                'form' => $form,
                'modele' => $modele,
            ]);
        }

    
            // Traiter le fichier uploadé
            $files = $form->get('nom')->getData();
            $file_name = date('Y-d-d-H-i-s') . '-' . (count($user->getMedia()) + 1) . '.' . $files->getClientOriginalExtension();
            $files->move($this->getParameter('upload_dir'), $file_name);
    
            // Enregistrer le média dans la base de données
            $media->setNom($file_name);
            $manager->persist($media);
            $user->addMedium($media);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Média créé');
            return $this->redirectToRoute('app_modele_media_create');
        }

        return $this->render('modele/media_create.html.twig', [
            'form' => $form,
            'modele' => $modele,
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
            return $this->redirectToRoute('app_modele_media_create');
        }

        #[Route('/media/list', name: 'media_list')]
        public function media_list(Request $request): Response
        {
            // Récupérer l'utilisateur connecté
            $user = $this->getUser();

                    // Vérifier si l'utilisateur est un modèle et récupérer son modèle
        $modele = $user->getModele();

            // Récupérer les médias de l'utilisateur
            $medias = $user->getMedia();

            return $this->render('modele/media_list.html.twig', [
                'user' => $user,
                'medias' => $medias,
                'modele' => $modele,
            ]);
        }


}
