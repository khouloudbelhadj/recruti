<?php

namespace App\Controller;

use App\Entity\Biblio;
use App\Form\BiblioType;
use App\Repository\BiblioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/biblio')]
class BiblioController extends AbstractController
{
    #[Route('/', name: 'app_biblio_index', methods: ['GET'])]
    public function index(BiblioRepository $biblioRepository): Response
    {
        return $this->render('biblio/index.html.twig', [
            'biblios' => $biblioRepository->findAll(),
        ]);
    }

    #[Route('/front', name: 'app_biblio_index_front', methods: ['GET'])]
    public function indexfront(BiblioRepository $biblioRepository): Response
    {
        return $this->render('biblio/indexfront.html.twig', [
            'biblios' => $biblioRepository->findAll(),
        ]);
    }

    #[Route('/frontbibliocondidat', name: 'app_biblio_show_front_ressource', methods: ['GET'])]
    public function showfrontbiblioressource(BiblioRepository $biblioRepository): Response
    {
        return $this->render('biblio/show_front_biblio_ressource.html.twig', [
            'biblios' => $biblioRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_biblio_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $biblio = new Biblio();
        $form = $this->createForm(BiblioType::class, $biblio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($biblio);
            $entityManager->flush();

            return $this->redirectToRoute('app_biblio_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('biblio/new.html.twig', [
            'biblio' => $biblio,
            'form' => $form,
        ]);
    }

    #[Route('/newfront', name: 'app_biblio_new_front', methods: ['GET', 'POST'])]
    public function newfront(Request $request, EntityManagerInterface $entityManager): Response
    {
        $biblio = new Biblio();
        $form = $this->createForm(BiblioType::class, $biblio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($biblio);
            $entityManager->flush();

            return $this->redirectToRoute('app_biblio_index_front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('biblio/newfront.html.twig', [
            'biblio' => $biblio,
            'form' => $form,
        ]);
    }   

    #[Route('/{id}', name: 'app_biblio_show', methods: ['GET'])]
    public function show(Biblio $biblio): Response
    {
        return $this->render('biblio/show.html.twig', [
            'biblio' => $biblio,
        ]);
    }

    #[Route('/front/{id}', name: 'app_biblio_show_front', methods: ['GET'])]
    public function showfront(Biblio $biblio): Response
    {
        return $this->render('biblio/showfront.html.twig', [
            'biblio' => $biblio,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_biblio_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Biblio $biblio, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BiblioType::class, $biblio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_biblio_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('biblio/edit.html.twig', [
            'biblio' => $biblio,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/editfront', name: 'app_biblio_edit_front', methods: ['GET', 'POST'])]
    public function editfront(Request $request, Biblio $biblio, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BiblioType::class, $biblio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_biblio_index_front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('biblio/editfront.html.twig', [
            'biblio' => $biblio,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_biblio_delete', methods: ['POST'])]
    public function delete(Request $request, Biblio $biblio, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$biblio->getId(), $request->request->get('_token'))) {
            $entityManager->remove($biblio);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_biblio_index', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('front/{id}', name: 'app_biblio_delete_front', methods: ['POST'])]
    public function deletefront(Request $request, Biblio $biblio, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$biblio->getId(), $request->request->get('_token'))) {
            $entityManager->remove($biblio);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_biblio_index_front', [], Response::HTTP_SEE_OTHER);
    }    
}
