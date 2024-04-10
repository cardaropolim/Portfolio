<?php

namespace App\Controller;

use App\Entity\Modele;
use App\Form\ModeleFormType;
use App\Form\TarifsType;
use App\Repository\ModeleRepository;
use Symfony\Component\Form\FormView;
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

     #[Route('/disponibilites', name: 'disponibilites')]
     public function disponibilites(): Response
     {
         return $this->render('modele/disponibilites.html.twig', []);

     }
    #[Route('/mobilite', name: 'mobilite')]

    public function mobilite(): Response
    {
        return $this->render('modele/mobilite.html.twig', []);
    }


    #[Route('/reseaux_sociaux', name: 'reseaux_sociaux')]

    public function reseaux_sociaux(): Response
    {
        return $this->render('modele/reseaux-sociaux.html.twig', []);
    }
    #[Route('/statistiques', name: 'statistiques')]

    public function statistiques(): Response
    {
        return $this->render('modele/statistiques.html.twig', []);
    }

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
    public function tarifs_form(Request $request, EntityManagerInterface $entityManager, ModeleRepository $model): Response
    {
    // // // // Création d'une nouvelle instance de Modele
    //   $builder = $entityManager->createQueryBuilder();
    
     // Récupérer l'ID de l'utilisateur connecté
     $connectedUserId = $this->getUser()->getId();

     
    //  $query = $builder->select('m')->from
    //  ('App\Entity\Modele', 'm')->where('m.user = :id')->setParameter
    //  ('id', $connectedUserId)->getQuery();

    //  $results = $query->getResult();
    //  $currentUser = !empty($results) ? $results[0] : null;

    

     // Récupérer l'entité Modele pour l'utilisateur connecté
     $currentUser = $model->findOneBy(['user' => $connectedUserId]);

     // Si aucune entité Modele n'est trouvée, créer une nouvelle instance
    if (!$currentUser) {
        $currentUser = new Modele();
        $currentUser->setUser($this->getUser());
    }

    // Créer le formulaire en utilisant le type de formulaire TarifsType et l'entité Modele récupérée
    $form = $this->createForm(TarifsType::class, $currentUser);
    $form->handleRequest($request);

    // $form->getErrors(true);

    if ($form->isSubmitted() && $form->isValid()) {
       
        // Récupération des données du formulaire
        $inputs = $form->get('inputs')->getData();

        // Ajout des inputs au modèle
        foreach ($inputs as $input) {
            $currentUser->addInput($input);
        }

        // Récupération des inputs à supprimer
        $removedInputs = $form->get('removedInputs')->getData();
        foreach ($removedInputs as $removedInput) {
            $currentUser->removeInput($removedInput);
            }

        // Persistez et sauvegardez le modèle dans la base de données
        $entityManager->persist($currentUser);
        $entityManager->flush();

       

        // Rediriger vers la page d'accueil ou toute autre page appropriée
        return $this->redirectToRoute('app_modele_index');
    }

    return $this->render('modele/tarifs.html.twig', [
        'form' => $form->createView(),
    ]);

    
}


}
