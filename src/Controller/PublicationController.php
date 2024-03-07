<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Form\PublicationType;
use App\Form\PubEditType;
use App\Entity\Media;
use App\Entity\Commentaire;
use App\Repository\PublicationRepository;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('/publication')]
class PublicationController extends AbstractController
{
    #[Route('/', name: 'app_publication_index', methods: ['GET'])]
    public function index(LikeRepository $likerepo ,PublicationRepository $publicationRepository): Response
    {

        $publications = $publicationRepository->findAll(); 
       
        $forms = [];
     foreach (array_reverse($publications) as $publication) {
         $form = $this->createForm(PubEditType::class, $publication);
        $forms[] = $form->createView();
     }
        //  foreach ($publications as $publication) {
      //        $count = $likerepo->getCountByPublicationId($publication->getId());
        //   $pubLikes[] = 
         //     $count  ; }

        
      //  $reversedLikes = array_reverse($pubLikes);

        return $this->render('publication/_publications_list.html.twig', [
            'publications' => $publications,
            'forms' => $forms,
        //    'pubLikes' => $reversedLikes,
        ]);
        
    }
    #[Route('/puback', name: 'publicationback', methods: ['GET'])]
    public function indexback(PublicationRepository $publicationRepository): Response
    {
    

        return $this->render('publication/_index_back.html.twig', [
            'publications' => $publicationRepository->findAll(),
           
        ]);
    
    }
    #[Route('/profile', name: 'app_publication_profile', methods: ['GET','POST'])]
    public function profileView(EntityManagerInterface $entityManager,Request $request, PublicationRepository $publicationRepository): Response
    {
    
        $publication = new Publication();
        $publication->addMedium(new Media());
       // $publication->addCommentaire(new Commentaire()); // Ajout d'un commentaire vide
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $currentDate = new \DateTime('now');
            $formattedDate = $currentDate->format('Y-m-d H:i:s');
            $publication->setDateCreationpub($currentDate) ;
            $entityManager->persist($publication);
            $entityManager->flush();

          //  return $this->redirectToRoute('app_publication_profile', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('publication/profile.html.twig', [
            'publication' => $publication,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'app_publication_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $publication = new Publication();
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $currentDate = new \DateTime('now');
            $formattedDate = $currentDate->format('Y-m-d H:i:s');
            $publication->setDateCreationpub($currentDate) ;
            $entityManager->persist($publication);
            $entityManager->flush();

          //  return $this->redirectToRoute('app_publication_profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('publication/new.html.twig', [
            'publication' => $publication,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_publication_show', methods: ['GET'])]
    public function show(Publication $publication): Response
     {
        return $this->render('publication/show.html.twig', [
            'publication' => $publication,
       ]);
    
   
    

    return $this->render('publication/_index_back.html.twig', [
        'publications' => $publicationRepository->findAll(),
       
    ]);
    }
    #[Route('/{id}/edit', name: 'app_publication_edit', methods: ['GET','POST'])]
    public function edit(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
{
    // Récupérer le contenu envoyé dans le formulaire
    $contenu = $request->request->get('contenu');

    // Vérifier si le contenu de la publication a été envoyé
    if ($contenu !== null) {
        // Mettre à jour le contenu de la publication
        $publication->setContenu($contenu);

        // Enregistrer les modifications dans la base de données
        $entityManager->flush();

        // Retourner une réponse JSON pour indiquer le succès de la mise à jour
       // return new RedirectResponse($urlGenerator->generate('app_publication_profile'));
       return $this->redirectToRoute('app_publication_profile', [], Response::HTTP_SEE_OTHER);
    }

    // Retourner une réponse JSON en cas d'erreur ou de données manquantes
    return new JsonResponse(['error' => 'Missing or invalid data'], Response::HTTP_BAD_REQUEST);
}
  


    #[Route('/{id}', name: 'app_publication_delete', methods: ['POST'])]
    public function delete(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
   
    if ($this->isCsrfTokenValid('delete'.$publication->getId(), $request->request->get('_token'))) {
        // Supprimer chaque média associé à la publication
        foreach ($publication->getMedia() as $media) {
            // Vérifier si la propriété chemin n'est pas null avant de l'utiliser
            if ($media->getChemin() !== null) {
                // Supprimer le fichier physique si nécessaire
                // Supprimer l'entité média
                $entityManager->remove($media);
            }
        }

        // Supprimer la publication elle-même
        $entityManager->remove($publication);
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_publication_profile', [], Response::HTTP_SEE_OTHER);

   }
   #[Route('delete/{id}', name: 'app_publication_delete_back', methods: ['POST'])]
   public function deleteback(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
   {
      if ($this->isCsrfTokenValid('delete'.$publication->getId(), $request->request->get('_token'))) 
      {
      

       // Supprimer la publication elle-même
       $entityManager->remove($publication);
       $entityManager->flush();
      }

     return $this->redirectToRoute('publicationback', [], Response::HTTP_SEE_OTHER);

   }

}

