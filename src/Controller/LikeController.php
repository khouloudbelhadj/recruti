<?php

namespace App\Controller;

use App\Entity\Like;
use App\Entity\User;
use App\Entity\Publication;
use App\Form\LikeType;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
#[Route('/like')]
class LikeController extends AbstractController
{
    #[Route('/', name: 'app_like_index', methods: ['GET'])]
    public function index(LikeRepository $likeRepository): Response
    {
        return $this->render('like/index.html.twig', [
            'likes' => $likeRepository->findAll(),
        ]);
    }

    // #[Route('/{idP}/{idU}/new', name: 'app_like_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager, int $idP,int $idU): Response
    // {
    //     $like = new Like();
    //     $user = new User();
    //     $user = $entityManager->getRepository(User::class)->find($idU);
    //     $publication = $entityManager->getRepository(Publication::class)->find($idP);

    //     $currentDate = new \DateTime('now');
    //     $formattedDate = $currentDate->format('Y-m-d H:i:s');
    //     $like->setDateCreationLike($currentDate) ;
    //     $like->setUser($user);
    //     $like->setPublication($publication);
        

    //         $entityManager->persist($like);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_publication_profile', [], Response::HTTP_SEE_OTHER);

    // }
    #[Route('/{publicationId}/add', name: 'like_add', methods: [ 'POST'])]
    public function addLike(Request $request, $publicationId): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $publication = $entityManager->getRepository(Publication::class)->find($publicationId);
        $user = $entityManager->getRepository(User::class)->find(1);
       // $user = $this->getUser();

        // Vérifier si la publication existe
        if (!$publication) {
            return new JsonResponse(['error' => 'Publication not found'], Response::HTTP_NOT_FOUND);
        }

        // Vérifier si l'utilisateur a déjà aimé la publication
        $existingLike = $entityManager->getRepository(Like::class)->findOneBy(['user' => $user, 'publication' => $publication]);

        if ($existingLike) {
            return new JsonResponse(['error' => 'You have already liked this publication'], Response::HTTP_BAD_REQUEST);
        }

        // Ajouter un like à la publication
        $like = new Like();
        $like->setPublication($publication);
        $like->setUser($user);
        $currentDate = new \DateTime('now');
        $formattedDate = $currentDate->format('Y-m-d H:i:s');
            $like->setDateCreationLike($currentDate) ;
        $entityManager->persist($like);
        $entityManager->flush();

        return new JsonResponse(['success' => true]);
    }

    #[Route('/{id}', name: 'app_like_show', methods: ['GET'])]
    public function show(Like $like): Response
    {
        return $this->render('like/show.html.twig', [
            'like' => $like,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_like_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Like $like, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LikeType::class, $like);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_like_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('like/edit.html.twig', [
            'like' => $like,
            'form' => $form,
        ]);
    }

    // #[Route('/{id}', name: 'app_like_delete', methods: ['POST'])]
    // public function delete(Request $request, Like $like, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$like->getId(), $request->request->get('_token'))) {
    //         $entityManager->remove($like);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_like_index', [], Response::HTTP_SEE_OTHER);
    // }
    #[Route('/{publicationId}/remove', name: 'like_remove', methods: ['POST'])]
    public function removeLike(Request $request, $publicationId): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $publication = $entityManager->getRepository(Publication::class)->find($publicationId);
        $user = $entityManager->getRepository(User::class)->find(1);
        //$user = $this->getUser();

        // Vérifier si la publication existe
        if (!$publication) {
            return new JsonResponse(['error' => 'Publication not found'], Response::HTTP_NOT_FOUND);
        }

        // Vérifier si l'utilisateur a déjà aimé la publication
        $like = $entityManager->getRepository(Like::class)->findOneBy(['user' => $user, 'publication' => $publication]);

         if (!$like) {
             return new JsonResponse(['error' => 'You have not liked this publication'], Response::HTTP_BAD_REQUEST);
         }

        // Supprimer le like de la publication
        $entityManager->remove($like);
        $entityManager->flush();

        return new JsonResponse(['success' => true]);
    }
}
