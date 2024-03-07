<?php

namespace App\Controller;

use App\Entity\Biblio;
use App\Form\BiblioType;
use App\Repository\BiblioRepository;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;


#[Route('/biblio')]
class BiblioController extends AbstractController
{
//back 

////index
    #[Route('/', name: 'app_biblio_index', methods: ['GET'])]
    public function index(Request $request,BiblioRepository $biblioRepository): Response
    {
        $nameSearch = $request->query->get('nameSearch');
        $fieldSearch = $request->query->get('fieldSearch');
        $dateSearch = $request->query->get('dateSearch');
    
        $queryBuilder = $biblioRepository->createQueryBuilder('b');
    
        if ($nameSearch) {
            $queryBuilder->andWhere('b.nom_b LIKE :nameSearch')
                ->setParameter('nameSearch', '%' . $nameSearch . '%');
        }
    
        if ($fieldSearch) {
            $queryBuilder->andWhere('b.domaine_b LIKE :fieldSearch')
                ->setParameter('fieldSearch', '%' . $fieldSearch . '%');
        }
    
        if ($dateSearch) {
            $queryBuilder->andWhere('b.date_creation_b LIKE :dateSearch')
                ->setParameter('dateSearch', '%' . $dateSearch . '%');
        }
        $biblios = $queryBuilder->getQuery()->getResult();
        return $this->render('biblio/index.html.twig', [
            'biblios' => $biblios,
        ]);
    }

 ///Add Library   
    #[Route('/new', name: 'app_biblio_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $biblio = new Biblio();
        $filesystem = new Filesystem();
        $form = $this->createForm(BiblioType::class, $biblio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($biblio);
            $entityManager->flush();

            $uploadedFile = $form->get('image_b')->getData();
            $formData = $uploadedFile->getPathname();
            $sourcePath = strval($formData);
            $destinationPath = 'uploads/imgLibraries/'.$biblio->getNomB().strval($biblio->getId()).'.png';
            $biblio->setImageB($destinationPath);
            $filesystem->copy($sourcePath,$destinationPath);           
          
            $entityManager->persist($biblio);
            $entityManager->flush();

            return $this->redirectToRoute('app_biblio_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('biblio/new.html.twig', [
            'biblio' => $biblio,
            'form' => $form,
        ]);
    }
 
////Show Library    
    #[Route('/{id}', name: 'app_biblio_show', methods: ['GET'])]
    public function show(Biblio $biblio): Response
    {
        return $this->render('biblio/show.html.twig', [
            'biblio' => $biblio,
        ]);
    }

////Edit Library
    #[Route('/{id}/edit', name: 'app_biblio_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Biblio $biblio, EntityManagerInterface $entityManager): Response
    {
        $filesystem = new Filesystem();    
        $form = $this->createForm(BiblioType::class, $biblio);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
          
            $uploadedFile = $form->get('image_b')->getData();
            $formData = $uploadedFile->getPathname();
            $sourcePath = strval($formData);
            $destinationPath = 'uploads/imgLibraries/'.$biblio->getNomB().strval($biblio->getId()).'.png';
            $biblio->setImageB($destinationPath);
            $filesystem->copy($sourcePath,$destinationPath);

            $entityManager->persist($biblio);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_biblio_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('biblio/edit.html.twig', [
            'biblio' => $biblio,
            'form' => $form,
        ]);
    }

//// Delete Library    
    #[Route('/{id}', name: 'app_biblio_delete', methods: ['POST'])]
    public function delete(Request $request, Biblio $biblio, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$biblio->getId(), $request->request->get('_token'))) {
            $entityManager->remove($biblio);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_biblio_index', [], Response::HTTP_SEE_OTHER);
    }

 ///Afficher les ressources de chaque biblio
 #[Route('/{id}/ressources', name: 'app_biblio_ressources_back', methods: ['GET'])]
 public function showResourcesBack(Biblio $biblio): Response
 {
     return $this->render('biblio/biblio_resources_back.html.twig', [
         'biblio' => $biblio,
     ]);
 }
  
  
//front 

/////Show for recru
#[Route('/front/recru', name: 'app_biblio_index_front_recru', methods: ['GET'])]
public function indexfrontrecru(Request $request,BiblioRepository $biblioRepository): Response
{
    $nameSearch = $request->query->get('nameSearch');
    $fieldSearch = $request->query->get('fieldSearch');
    $dateSearch = $request->query->get('dateSearch');

    $queryBuilder = $biblioRepository->createQueryBuilder('b');

    if ($nameSearch) {
        $queryBuilder->andWhere('b.nom_b LIKE :nameSearch')
            ->setParameter('nameSearch', '%' . $nameSearch . '%');
    }

    if ($fieldSearch) {
        $queryBuilder->andWhere('b.domaine_b LIKE :fieldSearch')
            ->setParameter('fieldSearch', '%' . $fieldSearch . '%');
    }

    if ($dateSearch) {
        $queryBuilder->andWhere('b.date_creation_b LIKE :dateSearch')
            ->setParameter('dateSearch', '%' . $dateSearch . '%');
    }

    $biblios = $queryBuilder->getQuery()->getResult();
    return $this->render('biblio/indexfrontrecru.html.twig', [
        'biblios' => $biblios,
    ]);
}



////Show Library
#[Route('/front/{id}', name: 'app_biblio_show_front_recru', methods: ['GET'])]
public function showfront(Biblio $biblio): Response
{
    return $this->render('biblio/showfrontrecru.html.twig', [
        'biblio' => $biblio,
    ]);
}



/////add library
#[Route('/newfront', name: 'app_biblio_new_front', methods: ['GET', 'POST'])]
public function newfront(Request $request, EntityManagerInterface $entityManager): Response
{
    $biblio = new Biblio();
    $filesystem = new Filesystem();
    $form = $this->createForm(BiblioType::class, $biblio);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($biblio);
        $entityManager->flush();
 
        $uploadedFile = $form->get('image_b')->getData();
        $formData = $uploadedFile->getPathname();
        $sourcePath = strval($formData);
        $destinationPath = 'uploads/imgLibraries/'.$biblio->getNomB().strval($biblio->getId()).'.png';
        $biblio->setImageB($destinationPath);
        $filesystem->copy($sourcePath,$destinationPath);

        $entityManager->persist($biblio);
        $entityManager->flush();

        return $this->redirectToRoute('app_biblio_index_front_recru', [], Response::HTTP_SEE_OTHER);
    }
    return $this->renderForm('biblio/newfront.html.twig', [
        'biblio' => $biblio,
        'form' => $form,
    ]);
}

/////Edit Library
#[Route('/{id}/editfront', name: 'app_biblio_edit_front', methods: ['GET', 'POST'])]
public function editfront(Request $request, Biblio $biblio, EntityManagerInterface $entityManager): Response
{
    $filesystem = new Filesystem();
    $form = $this->createForm(BiblioType::class, $biblio);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
       
        $uploadedFile = $form->get('image_b')->getData();
        $formData = $uploadedFile->getPathname();
        $sourcePath = strval($formData);
        $destinationPath = 'uploads/imgLibraries/'.$biblio->getNomB().strval($biblio->getId()).'.png';
        $biblio->setImageB($destinationPath);
        $filesystem->copy($sourcePath,$destinationPath);
      
        $entityManager->persist($biblio);
        $entityManager->flush();

        return $this->redirectToRoute('app_biblio_index_front_recru', [], Response::HTTP_SEE_OTHER);
    }
    return $this->renderForm('biblio/editfront.html.twig', [
        'biblio' => $biblio,
        'form' => $form,
    ]);
}

////Delete Library
#[Route('front/{id}', name: 'app_biblio_delete_front', methods: ['POST'])]
public function deletefront(Request $request, Biblio $biblio, EntityManagerInterface $entityManager): Response
{
    if ($this->isCsrfTokenValid('delete'.$biblio->getId(), $request->request->get('_token'))) {
        $entityManager->remove($biblio);
        $entityManager->flush();
    }
    return $this->redirectToRoute('app_biblio_index_front_recru', [], Response::HTTP_SEE_OTHER);
}  

///Afficher les ressources de chaque biblio
#[Route('/front/{id}/ressources', name: 'app_biblio_ressources_front', methods: ['GET'])]
public function showResourcesFront(Biblio $biblio): Response
{
    return $this->render('biblio/biblio_resources_front.html.twig', [
        'biblio' => $biblio,
    ]);
}

}
