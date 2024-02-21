<?php

namespace App\Controller;

use App\Entity\Condidature;
use App\Form\CondidatureType;
use App\Repository\CondidatureRepository;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/condidature')]
class CondidatureController extends AbstractController
{
    #[Route('/', name: 'app_condidature_index', methods: ['GET'])]
    public function index(CondidatureRepository $condidatureRepository): Response
    {
        return $this->render('condidature/index.html.twig', [
            'condidatures' => $condidatureRepository->findAll(),
        ]);
    }
    #[Route('/front', name: 'app_condidature_index_front', methods: ['GET'])]
    public function indexfront(CondidatureRepository $condidatureRepository): Response
    {
        return $this->render('condidature/indexfront.html.twig', [
            'condidatures' => $condidatureRepository->findAll(),
        ]);
    }
    #[Route('/new', name: 'app_condidature_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CondidatureRepository $condidatureRepo): Response
    {
        $condidature = new Condidature();
        $filesystem = new Filesystem();
        $form = $this->createForm(CondidatureType::class, $condidature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $condidatureRepo->save($condidature,true);

            $uploadedFile = $form->get('cv_c')->getData();
            $formData = $uploadedFile->getPathname();
            $sourcePath = strval($formData);
            $destinationPath = 'uploads/cvs/'.$condidature->getNomC().strval($condidature->getId()).'.png';
            $condidature->setCvC($destinationPath);
            $filesystem->copy($sourcePath,$destinationPath);

            $condidatureRepo->save($condidature,true);

            return $this->redirectToRoute('app_condidature_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('condidature/new.html.twig', [
            'condidature' => $condidature,
            'form' => $form,
        ]);
    }
    #[Route('/newcondidature', name: 'app_condidature_new_front', methods: ['GET', 'POST'])]
    public function newfront(Request $request, EntityManagerInterface $entityManager): Response
    {
        $condidature = new Condidature();
        $filesystem = new Filesystem();
        $form = $this->createForm(CondidatureType::class, $condidature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form->get('cv_c')->getData();
            $formData = $uploadedFile->getPathname();
            $sourcePath = strval($formData);
            $destinationPath = 'uploads/cvs/'.$condidature->getNomC().strval($condidature->getId()).'.png';
            $condidature->setCvC($destinationPath);
            $filesystem->copy($sourcePath,$destinationPath);

            $entityManager->persist($condidature);
            $entityManager->flush();

            return $this->redirectToRoute('app_condidature_index_front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('condidature/newfront.html.twig', [
            'condidature' => $condidature,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_condidature_show', methods: ['GET'])]
    public function show(Condidature $condidature): Response
    {
        return $this->render('condidature/show.html.twig', [
            'condidature' => $condidature,
        ]);
    }
    #[Route('front/{id}', name: 'app_condidature_show_front', methods: ['GET'])]
    public function showfront(Condidature $condidature): Response
    {
        return $this->render('condidature/showfront.html.twig', [
            'condidature' => $condidature,
        ]);
    }

    #[Route('/{id}/editfront', name: 'app_condidature_edit_front', methods: ['GET', 'POST'])]
    public function editfront(Request $request, Condidature $condidature, EntityManagerInterface $entityManager): Response
    {
        $filesystem = new Filesystem();
        $form = $this->createForm(CondidatureType::class, $condidature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $uploadedFile = $form->get('cv_c')->getData();
            $formData = $uploadedFile->getPathname();
            $sourcePath = strval($formData);
            $destinationPath = 'uploads/cvs/'.$condidature->getNomC().strval($condidature->getId()).'.png';
            $condidature->setCvC($destinationPath);
            $filesystem->copy($sourcePath,$destinationPath);

            $entityManager->flush();

            return $this->redirectToRoute('app_condidature_index_front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('condidature/editfront.html.twig', [
            'condidature' => $condidature,
            'form' => $form,
        ]);
    }
    #[Route('{id}/edit', name: 'app_condidature_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Condidature $condidature, EntityManagerInterface $entityManager): Response
    {
        $filesystem = new Filesystem();
        $form = $this->createForm(CondidatureType::class, $condidature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form->get('cv_c')->getData();
            $formData = $uploadedFile->getPathname();
            $sourcePath = strval($formData);
            $destinationPath = 'uploads/cvs/'.$condidature->getNomC().strval($condidature->getId()).'.png';
            $condidature->setCvC($destinationPath);
            $filesystem->copy($sourcePath,$destinationPath);
            
            $entityManager->flush();

            return $this->redirectToRoute('app_condidature_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('condidature/edit.html.twig', [
            'condidature' => $condidature,
            'form' => $form,
        ]);
    }

    #[Route('front/{id}', name: 'app_condidature_delete_front', methods: ['POST'])]
    public function deletefront(Request $request, Condidature $condidature, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$condidature->getId(), $request->request->get('_token'))) {
            $entityManager->remove($condidature);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_condidature_index_front', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/{id}', name: 'app_condidature_delete', methods: ['POST'])]
    public function delete(Request $request, Condidature $condidature, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$condidature->getId(), $request->request->get('_token'))) {
            $entityManager->remove($condidature);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_condidature_index', [], Response::HTTP_SEE_OTHER);
    }
}
