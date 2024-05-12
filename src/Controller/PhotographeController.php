<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\Photographe;
use App\Entity\Tarifs;
use App\Entity\User;
use App\Form\MediaType;
use App\Form\PhotographeFormType;
use App\Form\RegistrationFormType;
use App\Form\TarifType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException as ExceptionAccessDeniedException;

#[Route('/photographe', name: 'app_photographe_'), IsGranted('ROLE_PHOTOGRAPHE')]
class PhotographeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Security $security): Response
    {
        $user = $security->getUser();

        if (!$this->getUser()->getPhotographe()) {
            return $this->redirectToRoute('app_photographe_photographe_profile');
        }

        return $this->render('photographe/index.html.twig', [
            'controller_name' => 'PhotographeController',
            "user" => $user
        ]);
    }

    // méthode pour fill, récupérer, modifier informations photographe //
    #[Route('/informations-profile', name: 'photographe_profile')]
    public function profile(Request $request, EntityManagerInterface $entityManager): Response
    {
    // Crée une nouvelle instance de l'entité "Modele"
        $photographe = new Photographe();
         // Vérifie si l'utilisateur actuel a déjà un profil de modèle enregistré
        if ($this->getUser()->getPhotographe()) {
        // Si oui, récupère les données du modèle de l'utilisateur actuel
            $photographe = $this->getUser()->getPhotographe();
        }

        $photographe->setUser($this->getUser());
            // Crée un formulaire Symfony à partir de la classe de formulaire "ModeleFormType" et l'associe aux données du modèle
        $form = $this->createForm(PhotographeFormType::class, $photographe);

    // Traite la requête HTTP pour remplir et valider le formulaire.
        $form->handleRequest($request);

    // Vérifie si le formulaire a été soumis et si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            // Persiste les données du modèle en base de données
            $entityManager->persist($photographe);
        // Applique les changements en base de données
            $entityManager->flush();
        // Redirige l'utilisateur vers une autre page après la soumission réussie du formulaire
            return $this->redirectToRoute('app_photographe_index');
        }
        return $this->render('photographe/profile-form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

        // remplir, récupérer, modifier informations modèle //
        #[Route('/user_informations/{id}', name: 'user_informations')]
        public function profile_user(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, $id, UserPasswordHasherInterface $userPasswordHasher, Security $security): Response
        {
            if ($id) {
                $user = $userRepository->find($id);
            } else {
                $user = new User();
            }
    
            $form = $this->createForm(RegistrationFormType::class, $user);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $user->setRoles([$form->get('role')->getData()]);
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
    
                $entityManager->persist($user);
                $entityManager->flush();
    
                return $this->redirectToRoute('app_photographe_index');
            }
    
            return $this->render('photographe/registration-form.html.twig', [
                'form' => $form->createView(),
            ]);
        }

    // accéder aux FAQ 
    #[Route('/FAQ', name: 'FAQ')]
    public function FAQ(): Response
    {
        return $this->render('photographe/FAQ.html.twig', []);
    }
        // accéder à la bibliothèque
    #[Route('/bibliotheque', name: 'bibliotheque')]
    public function bibliotheque(): Response
    {
        // Récupère les données de l'utilisateur   
        $user = $this->getUser();

        return $this->render('photographe/bibliotheque.html.twig', [
            'user' => $user,
        ]);
    }

    // route book photographe //
    #[Route('/book_photographe', name: 'book_photographe')]

    public function book_modele(): Response
    {
        // Récupère les données de l'utilisateur
        $user = $this->getUser(); 
        $photographe = $user->getPhotographe();

        return $this->render('photographe/book-photographe.html.twig', [
            'user' => $user,
            'photographe' => $photographe
        ]);
    }

    // fonction pour ajouter des prestations/tarifs //
    #[Route('/tarifs', name: 'tarifs_form')]
    public function tarifs_form(Request $request, EntityManagerInterface $entityManager): Response
    {
     // Récupère les tarifs associés à l'utilisateur connecté
        $tarifsUser = $this->getUser()->getTarifs();

        // Crée une nouvelle instance de l'entité Tarifs
        $tarif = new Tarifs();
        // Créer le formulaire en utilisant le type de formulaire TarifsType et l'entité Tarif créée
        $form = $this->createForm(TarifType::class, $tarif);
        $form->handleRequest($request);

         // Vérifie si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Associe le tarif à l'utilisateur actuel
            $tarif->setUser($this->getUser());
            // Persiste le tarif dans la base de données
            $entityManager->persist($tarif);
            $entityManager->flush();

            // Rediriger vers la page d'accueil
            return $this->redirectToRoute('app_photographe_index');
        }

        return $this->render('photographe/tarifs.html.twig', [
            'form' => $form->createView(),
            'tarifsUser' => $tarifsUser
        ]);
    }
    // supprimer des prestations/tarifs 
    #[Route('/deleteTarifs/{id}', name: 'delete_tarifs')]
    public function deleteTarif(Request $request, EntityManagerInterface $entityManager, Tarifs $tarif): Response
    {
        // Vérifie si le token CSRF est valide
        if ($this->isCsrfTokenValid('delete' . $tarif->getId(), $request->request->get('_token'))) {
            // Supprime le tarif de la base de données
            $entityManager->remove($tarif);
            $entityManager->flush();

            // Message flash pour indiquer que le tarif a été supprimé avec succès
            $this->addFlash('success', 'Tarif supprimé avec succès.');
        } else {
            // Message flash pour indiquer que le token CSRF est invalide
            $this->addFlash('error', 'Token CSRF invalide.');
        }
        // Redirige vers la page d'accueil
        return $this->redirectToRoute('app_photographe_index');
    }

    #[Route('/editTarif/{id}', name: 'edit_tarif')]
    public function editTarif(Request $request, EntityManagerInterface $entityManager, Tarifs $tarif): Response
    {
        // Récupérer le tarif à partir de la base de données en utilisant son identifiant
        $tarif = $entityManager->getRepository(Tarifs::class)->find($tarif->getId());

        // Créer le formulaire en utilisant le type de formulaire TarifsType et le tarif récupéré
        $form = $this->createForm(TarifType::class, $tarif);
        $form->handleRequest($request);
    
         // Vérifie si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Met à jour le tarif dans la base de données
            $entityManager->flush();

            // Rediriger vers la page d'accueil
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


                        if ($media->getDestination() !== 'bibliotheque' && in_array($media->getDestination(), $sections)) {
                            $this->addFlash('danger', 'Section déjà utilisée');
                            return $this->redirectToRoute('app_photographe_media_create', [
                                'form' => $form,
                                'photographe' => $photographe,
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
            return $this->redirectToRoute('app_photographe_media_create');
        }

        return $this->render('photographe/media_create.html.twig', [
            'form' => $form,
            'photographe' => $photographe,
        ]); 
    }



    #[Route('/media/{id}/delete', name: 'media_delete')]
    public function media_delete(Media $media, Request $request, EntityManagerInterface $manager): Response
    {
        // Vérifier si l'utilisateur connecté est le propriétaire du média
        $user = $this->getUser();
        if ($user !== $media->getUser()) {
            throw new ExceptionAccessDeniedException("Vous n'avez pas la permission de supprimer ce média.");
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
        $photographe = $user->getPhotographe();

        // Récupérer les médias de l'utilisateur
        $medias = $user->getMedia();

        return $this->render('photographe/media_list.html.twig', [
            'user' => $user,
            'medias' => $medias,
            'photographe' => $photographe,
        ]);
    }}

