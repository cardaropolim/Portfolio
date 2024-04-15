<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\Modele;
use App\Entity\Tarifs;
use App\Form\MediaType;
use App\Form\ModeleFormType;
use App\Form\TarifType;
use App\Repository\ModeleRepository;
use App\Repository\UserRepository;
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
            return $this->redirectToRoute('app_modele_modeleprofile');
        }

        return $this->render('modele/index.html.twig', [
            'controller_name' => 'ModeleController',
        ]);
    }

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

    #[Route('/contacts', name: 'contacts')]
    public function contacts(): Response
    {
        return $this->render('modele/contacts.html.twig', []);
    }

    //  #[Route('/disponibilites', name: 'disponibilites')]
    //  public function disponibilites(): Response
    //  {
    //      return $this->render('modele/disponibilites.html.twig', []);

    //  }
    // #[Route('/mobilite', name: 'mobilite')]

    // public function mobilite(): Response
    // {
    //     return $this->render('modele/mobilite.html.twig', []);
    // }


    // #[Route('/reseaux_sociaux', name: 'reseaux_sociaux')]

    // public function reseaux_sociaux(): Response
    // {
    //     return $this->render('modele/reseaux-sociaux.html.twig', []);
    // }
    // #[Route('/statistiques', name: 'statistiques')]

    // public function statistiques(): Response
    // {
    //     return $this->render('modele/statistiques.html.twig', []);
    // }

    #[Route('/book_modele', name: 'book_modele')]

    public function book_modele(): Response
    {
        return $this->render('modele/book-modele.html.twig', []);
    }

    #[Route('/compte', name: 'compte')]
    public function compte(): Response
    {
        return $this->render('modele/compte.html.twig', []);
    }


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
    
        $media = new Media();
    
        $form = $this->createForm(MediaType::class, $media);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $files = $form->get('nom')->getData();
            $nbMedias = count($user->getMedia());
    
            $file_name = date('Y-d-d-H-i-s') . '-' . $media->getNom() . ($nbMedias + 1) . '.' . $files->getClientOriginalExtension();
    
            $files->move($this->getParameter('upload_dir'), $file_name);
    
            $media->setNom($file_name);
            $media->setNom($media->getNom() . ($nbMedias));
    
            $manager->persist($media);
            $user->addMedium($media);
            $manager->persist($user);
            $manager->flush();
    
            $this->addFlash('success', 'Média créé, vous pouvez en ajouter un autre et valider ou cliquer sur terminé pour voir le détail');
        }
    
        return $this->render('modele/media_create.html.twig', [
            'form' => $form->createView(),
            'modele' => $modele,
        ]);
    }
    

}
