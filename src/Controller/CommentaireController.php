<?php

namespace App\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\Commentaire;
use App\Entity\Publication;
use App\Entity\User;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


#[Route('/commentaire')]
class CommentaireController extends AbstractController
{
    #[Route('/', name: 'app_commentaire_index', methods: ['GET'])]
    public function index(CommentaireRepository $commentaireRepository): Response
    {
        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
        ]);
    }


    #[Route('/new/{publicationId}', name: 'app_commentaire_new', methods: ['POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, int $publicationId): Response
{
    // Récupérez les données postées dans la requête
    $contenuCom = $request->request->get('contenu_com');
   
    // Assurez-vous de traiter les données postées ici pour éviter les failles de sécurité

    // Créez une nouvelle instance de Commentaire et définissez les valeurs postées
    $commentaire = new Commentaire();
    $commentaire->setcontenu_com($contenuCom);
    

    // Assurez-vous de traiter d'autres valeurs nécessaires ici (par exemple, la date de création)

    // Persistez l'entité dans la base de données
    $currentDate = new \DateTime('now');
    $formattedDate = $currentDate->format('Y-m-d H:i:s');
    $commentaire->setdate_creationCom($currentDate) ;
   // Récupérer l'entité Publication correspondant à l'identifiant publicationId
   $publication = $entityManager->getRepository(Publication::class)->find($publicationId);
   if (!$publication) {
       // Gérer le cas où la publication n'est pas trouvée
       // Par exemple, renvoyer une réponse d'erreur ou rediriger vers une autre page
   }

   // Définir la publication du commentaire
   $commentaire->setPublication($publication);

   $user = $entityManager->getRepository(User::class)->find(1);
   if (!$user) {
       // Gérer le cas où la publication n'est pas trouvée
       // Par exemple, renvoyer une réponse d'erreur ou rediriger vers une autre page
   }

   // Définir la publication du commentaire
   $commentaire->setUser($user);

    $entityManager->persist($commentaire);
    $entityManager->flush();

   // Rafraîchissez la page actuelle
   //return new Response('', Response::HTTP_OK, ['content-type' => 'text/html']);
   return $this->redirectToRoute('app_publication_profile', [], Response::HTTP_SEE_OTHER);
}

   

    #[Route('/{id}/edit', name: 'app_commentaire_edit', methods: ['POST'])]
public function edit(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
{
    // Récupérer les données JSON envoyées dans la requête
    $data = json_decode($request->getContent(), true);

    // Vérifier si le contenu du commentaire a été envoyé
    if (isset($data['contenu_com'])) {
        // Mettre à jour le contenu du commentaire
        $commentaire->setcontenu_com($data['contenu_com']);
        
        // Enregistrer les modifications dans la base de données
        $entityManager->flush();

        // Retourner une réponse JSON pour indiquer le succès de la mise à jour
        return new JsonResponse(['success' => true]);
    }

    // Retourner une réponse JSON en cas d'erreur ou de données manquantes
    return new JsonResponse(['error' => 'Missing or invalid data'], Response::HTTP_BAD_REQUEST);
}

    #[Route('/{id}', name: 'app_commentaire_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        
            $entityManager->remove($commentaire);
            $entityManager->flush();
        

        return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/comback', name: 'commentaireback', methods: ['GET'])]
    public function indexback(CommentaireRepository $commentaireRepository): Response
    {
    

        return $this->render('commentaire/_index_back.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
           
        ]);
    
    }
    #[Route('delete/{id}', name: 'app_commentaire_delete_back', methods: ['POST'])]
    public function deleteback(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
       if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->request->get('_token'))) 
       {
       
 
        // Supprimer la publication elle-même
        $entityManager->remove($commentaire);
        $entityManager->flush();
       }
 
      return $this->redirectToRoute('commentaireback', [], Response::HTTP_SEE_OTHER);
 
    }
    #[Route('/{id}', name: 'app_commentaire_show', methods: ['GET'])]
    public function show(Commentaire $commentaire): Response
     {
        return $this->render('commentaire/show.html.twig', [
            'commentaire' => $commentaire,
       ]);
    
   
    

    return $this->render('commentaire/_index_back.html.twig', [
        'commentaires' => $commentaireRepository->findAll(),
       
    ]);
    }
   
    #[Route('/search/{id}', name: 'app_commentaire_search', methods: ['GET'])]
    public function searchComments(Request $request): JsonResponse
    {
       // $publicationId = $request->query->get('publicationId');
       $publicationId = $request->attributes->get('id');
        // Effectuez une logique pour récupérer les commentaires correspondant à l'ID de publication
        
        // Supposons que vous ayez une méthode dans votre Repository pour récupérer les commentaires par ID de publication
        $commentaires = $this->getDoctrine()->getRepository(Commentaire::class)->findBy(['publication' => $publicationId]);

        // Convertissez les commentaires en un tableau pour l'envoyer en tant que réponse JSON
        $responseData = [];
        foreach ($commentaires as $commentaire) {
            $responseData[] = [
                'id' => $commentaire->getId(),
                'user' => [
                    'id' => $commentaire->getUser()->getId(),
                    'Username' => $commentaire->getUser()->getUsername(),
                ],
                'Publication' => [
                    'id' => $commentaire->getPublication()->getId(),
                ],
                'contenu_com' => $commentaire->getcontenu_com(),
                'date_creationCom' => $commentaire->getdate_creationCom() ? $commentaire->getdate_creationCom()->format('Y-m-d H:i:s') : null,
                // Vous pouvez également inclure le formulaire de suppression ici si nécessaire
                'deleteForm' => $this->renderView('commentaire/_delete_back_form.html.twig', ['commentaire' => $commentaire]),
            ];
        }

        return new JsonResponse($responseData);
    }

}
