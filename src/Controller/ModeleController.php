<?php

namespace App\Controller;

<<<<<<< HEAD

use App\Entity\Media;
use App\Entity\Modele;
use App\Entity\Tarifs;

=======
use App\Entity\Gallerie;
use App\Entity\Media;
use App\Entity\Modele;
use App\Entity\Tarifs;
use App\Form\GallerieType;
>>>>>>> a46aa24c9c19036fbc43f3424e16afbe3cedffd2
use App\Form\MediaType;
use App\Form\ModeleFormType;
use App\Form\TarifType;
use App\Repository\MediaRepository;
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


<<<<<<< HEAD
    // fonction pour fill, récupérer, modifier informations modèle //
    #[Route('/informations-profile', name: 'modeleprofile')]
    public function profile(Request $request, EntityManagerInterface $entityManager): Response
    {
        $modele = new Modele();
        if ($this->getUser()->getModele()) {
            $modele = $this->getUser()->getModele();
=======
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
>>>>>>> a46aa24c9c19036fbc43f3424e16afbe3cedffd2
        }

        // not in used for now //

        #[Route('/contacts', name: 'contacts')]
        public function contacts(): Response
        {
            return $this->render('modele/contacts.html.twig', []);
        }
    
        #[Route('/compte', name: 'compte')]
        public function compte(): Response
        {
            return $this->render('modele/compte.html.twig', []);
        }

        // route book modele //

    // not in used for now //
    #[Route('/compte', name: 'compte')]
    public function compte(): Response
    {
        return $this->render('modele/compte.html.twig', []);
    }

    // route book modele //
    #[Route('/book_modele', name: 'book_modele')]
    public function book_modele(): Response
    {
        return $this->render('modele/book-modele.html.twig', [
            'user' => $this->getUser()
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


    //////////////////////// media //////////////////////////////
    // media create //
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
            foreach ($medias as $media) {
                $sections[] = $media->getDestination();
            }

            $media = new Media();

            $form = $this->createForm(MediaType::class, $media);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                foreach ($sections as $section) {
                    if ($media->getDestination() === $section) {
                        $this->addFlash('danger', 'Section déjà utilisée');
                        return $this->render('modele/media_create.html.twig', [
                            'form' => $form->createView(),
                            'modele' => $modele,
                        ]);
                    }
                }

                $files = $form->get('nom')->getData();

                $nbMedias = count($user->getMedia());

                $file_name = date('Y-d-d-H-i-s') . '-' . ($nbMedias + 1) . '.' . $files->getClientOriginalExtension();

                $files->move($this->getParameter('upload_dir'), $file_name);

                // traitement des images / sections 

                $media->setNom($file_name);

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
//////////////////////////////////////////////////////////////////////////////////////////
        // upload file //

        public function uploadFiles(array $files, array $imagesNames = []): array
        {
            foreach ($files as $imageFile) {
                if ($imageFile['file'] !== null) {
                    $imageName = date('Y-m-d-H-i-s') . '-' . uniqid() . '.' . $imageFile['file']->getClientOriginalExtension();

<<<<<<< HEAD
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
                            return $this->render('modele/media_create.html.twig', [
                                'form' => $form->createView(),
                                'modele' => $modele,
                            ]);
                        }
                    }
                }
            }

            $files = $form->get('nom')->getData();

            $nbMedias = count($user->getMedia());

            $file_name = date('Y-d-d-H-i-s') . '-' . ($nbMedias + 1) . '.' . $files->getClientOriginalExtension();

            $files->move($this->getParameter('upload_dir'), $file_name);

            // traitement des images / sections 

            $media->setNom($file_name);

            $manager->persist($media);
            $user->addMedium($media);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Média créé');
            return $this->redirectToRoute('app_modele_media_create');
=======
                    $imageFile['file']->move(
                        $this->getParameter('upload_dir'), // Chemin vers le répertoire d'upload
                        $imageName
                    );

                    $imagesNames[] = $imageName;
                } else {
                    $imagesNames[] = $imageFile['file'];
                }
            }

            return $imagesNames;
        }
        // delete files //

        public function deleteUploadedFiles(string $file): void
        {
            // verify file exists
        //if (file_exists($this->getParameter('upload_dir') . '/' . $file)) { //}
                unlink($this->getParameter('upload_dir') . '/' . $file);
        
>>>>>>> a46aa24c9c19036fbc43f3424e16afbe3cedffd2
        }

                
        #[Route('/delete', name: 'app_product_delete', methods: ['POST'])]
        public function delete(Request $request, Gallerie $gallerie, EntityManagerInterface $entityManager): Response
        {
            if ($this->isCsrfTokenValid('delete'.$gallerie->getId(), $request->getPayload()->get('_token'))) {
                $entityManager->remove($gallerie);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_modele_modeleprofile', [], Response::HTTP_SEE_OTHER);
        }

        // show files //
        #[Route('/show', name: 'app_product_show', methods: ['GET'])]
        public function show(Gallerie $gallerie): Response
        {
            return $this->render('modele/product/show.html.twig', [
                'gallerie' => $gallerie,
            ]);
        }

        // new files //
        #[Route('/gallerie', name: 'app_gallerie_new', methods: ['GET', 'POST'])]
        public function gallerie (Request $request, Gallerie $gallerie, EntityManagerInterface $entityManager, MediaRepository $mediaRepository): Response
        {
            $form = $this->createForm(GallerieType::class, $gallerie);
            $form->handleRequest($request);
        
            if ($form->isSubmitted() && $form->isValid()) {
                    //  dd($request->files->all());
                $files = $request->files->all();
                // ['gallerie']['medias'];
                $imagesNames = $this->uploadFiles($files);
        
                $medias = $gallerie->getMedias(); // Utilisation de getMedia() ici ()  )
        
                foreach ($medias as $key => $media) {
                    if ($media->getMedias() === null) {
                        foreach ($imagesNames as $key => $imageName) {
                            if ($key !== null) {
                                $media->setSrc($imageName);
                            }
                        }
                    }
                    $entityManager->persist($media);
                }
                $entityManager->flush();
        
                return $this->redirectToRoute('app_modele_app_product_show', [], Response::HTTP_SEE_OTHER);
            }
        
            return $this->render('modele/product/new.html.twig', [
                'gallerie' => $gallerie,
                'form' => $form,
            ]);
        }


}
