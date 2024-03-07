<?php

namespace App\Controller;

use App\Entity\Ressource;
use App\Form\RessourceType;
use App\Repository\RessourceRepository;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Knp\Component\Pager\PaginatorInterface;
use Pagerfanta\Pagerfanta;

#[Route('/ressource')]
class RessourceController extends AbstractController
{
//back    
////Index 
    #[Route('/', name: 'app_ressource_index', methods: ['GET'])]
    public function index(Request $request,RessourceRepository $ressourceRepository): Response
    {
        $titreSearch = $request->query->get('titreSearch');
        $typeSearch = $request->query->get('typeSearch');
        $dateSearch = $request->query->get('dateSearch');
    
        $queryBuilder = $ressourceRepository->createQueryBuilder('r');
    
        if ($titreSearch) {
            $queryBuilder->andWhere('r.titre_b LIKE :titreSearch')
                ->setParameter('titreSearch', '%' . $titreSearch . '%');
        }
    
        if ($typeSearch) {
            $queryBuilder->andWhere('r.type_b LIKE :typeSearch')
                ->setParameter('typeSearch', '%' . $typeSearch . '%');
        }
    
        if ($dateSearch) {
            $queryBuilder->andWhere('r.date_publica_b LIKE :dateSearch')
                ->setParameter('dateSearch', '%' . $dateSearch . '%');
        }
    
        $ressources = $queryBuilder->getQuery()->getResult();

        return $this->render('ressource/index.html.twig', [
            'ressources' => $ressources,
        ]);
    }

/////Add Resource
    #[Route('/new', name: 'app_ressource_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ressource = new Ressource();
        $filesystem = new Filesystem();
        $form = $this->createForm(RessourceType::class, $ressource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ressource);
            $entityManager->flush();

            $uploadedFile = $form->get('image_b_ressource')->getData();
            $formData = $uploadedFile->getPathname();
            $sourcePath = strval($formData);
            $destinationPath = 'uploads/imgResources/'.$ressource->getTitreB().strval($ressource->getId()).'.png';
            $ressource->setImageBRessource($destinationPath);
            $filesystem->copy($sourcePath,$destinationPath);           
          
            $entityManager->persist($ressource);
            $entityManager->flush();            

            return $this->redirectToRoute('app_ressource_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ressource/new.html.twig', [
            'ressource' => $ressource,
            'form' => $form,
        ]);
    }

////Show resource
    #[Route('/{id}', name: 'app_ressource_show', methods: ['GET'])]
    public function show(Ressource $ressource): Response
    {
        return $this->render('ressource/show.html.twig', [
            'ressource' => $ressource,
        ]);
    }


/////Edit Resource
    #[Route('/{id}/edit', name: 'app_ressource_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ressource $ressource, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RessourceType::class, $ressource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $uploadedFile = $form->get('image_b_ressource')->getData();
            $formData = $uploadedFile->getPathname();
            $sourcePath = strval($formData);
            $destinationPath = 'uploads/imgResources/'.$ressource->getTitreB().strval($ressource->getId()).'.png';
            $ressource->setImageBRessource($destinationPath);
            $filesystem->copy($sourcePath,$destinationPath);           
          
            $entityManager->persist($ressource);
            $entityManager->flush();
           
            return $this->redirectToRoute('app_ressource_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ressource/edit.html.twig', [
            'ressource' => $ressource,
            'form' => $form,
        ]);
    }

////Delete Resource    
    #[Route('/{id}', name: 'app_ressource_delete', methods: ['POST'])]
    public function delete(Request $request, Ressource $ressource, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ressource->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ressource);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ressource_index', [], Response::HTTP_SEE_OTHER);
    }
    

//front

/////Show for recru
#[Route('/front/recru', name: 'app_ressource_index_front_recru', methods: ['GET'])]
public function indexfrontrecru(Request $request,RessourceRepository $ressourceRepository,PaginatorInterface $paginator): Response
{
    $titreSearch = $request->query->get('titreSearch');
    $typeSearch = $request->query->get('typeSearch');
    $dateSearch = $request->query->get('dateSearch');
    $data=$ressourceRepository->findAll();

    $queryBuilder = $ressourceRepository->createQueryBuilder('r');

    if ($titreSearch) {
        $queryBuilder->andWhere('r.titre_b LIKE :titreSearch')
            ->setParameter('titreSearch', '%' . $titreSearch . '%');
    }

    if ($typeSearch) {
        $queryBuilder->andWhere('r.type_b LIKE :typeSearch')
            ->setParameter('typeSearch', '%' . $typeSearch . '%');
    }

    if ($dateSearch) {
        $queryBuilder->andWhere('r.date_publica_b LIKE :dateSearch')
            ->setParameter('dateSearch', '%' . $dateSearch . '%');
    }

    $ressources = $queryBuilder->getQuery()->getResult();

    $ressources=$paginator->paginate(
        $data,
        $request->query->getInt('page',1),
        6
    );

    return $this->render('ressource/indexfront.html.twig', [
        'ressources' => $ressources,
    ]);
}

////Show Details Resources
#[Route('/front/ressource/{id}', name: 'app_ressource_show_front', methods: ['GET'])]
public function showfrontressourcedetails(RessourceRepository $ressourceRepository): Response
{
    return $this->render('ressource/ressource_detail_front.html.twig', [
        'ressources' => $ressourceRepository->findAll(),
    ]);
}

////Show Resource
#[Route('front/{id}', name: 'app_ressource_show_front_recru', methods: ['GET'])]
public function showfront(Ressource $ressource): Response
{
    return $this->render('ressource/showfrontrecru.html.twig', [
        'ressource' => $ressource,
    ]);
}

////Add Resource
#[Route('/new/ressource', name: 'app_ressource_new_front', methods: ['GET', 'POST'])]
public function newfront(Request $request, EntityManagerInterface $entityManager): Response
{
    $ressource = new Ressource();
    $filesystem = new Filesystem();  
    $form = $this->createForm(RessourceType::class, $ressource);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
       
        $uploadedFile = $form->get('image_b_ressource')->getData();
        $formData = $uploadedFile->getPathname();
        $sourcePath = strval($formData);
        $destinationPath = 'uploads/imgResources/'.$ressource->getTitreB().strval($ressource->getId()).'.png';
        $ressource->setImageBRessource($destinationPath);
        $filesystem->copy($sourcePath,$destinationPath);      
      
      
        $entityManager->persist($ressource);
        $entityManager->flush();

        return $this->redirectToRoute('app_ressource_index_front_recru', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('ressource/newfront.html.twig', [
        'ressource' => $ressource,
        'form' => $form,
    ]);
}

////Edit Resource
#[Route('/{id}/editfront', name: 'app_ressource_edit_front', methods: ['GET', 'POST'])]
public function editfront(Request $request, Ressource $ressource, EntityManagerInterface $entityManager): Response
{
    $filesystem = new Filesystem();
    $form = $this->createForm(RessourceType::class, $ressource);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        $uploadedFile = $form->get('image_b_ressource')->getData();
        $formData = $uploadedFile->getPathname();
        $sourcePath = strval($formData);
        $destinationPath = 'uploads/imgResources/'.$ressource->getTitreB().strval($ressource->getId()).'.png';
        $ressource->setImageBRessource($destinationPath);
        $filesystem->copy($sourcePath,$destinationPath); 
     
        $entityManager->persist($ressource);      
        $entityManager->flush();

        return $this->redirectToRoute('app_ressource_index_front_recru', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('ressource/editfront.html.twig', [
        'ressource' => $ressource,
        'form' => $form,
    ]);
}

////Delete Resource
    #[Route('front/{id}', name: 'app_ressource_delete_front', methods: ['POST'])]
    public function deletefront(Request $request, Ressource $ressource, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ressource->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ressource);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ressource_index_front_recru', [], Response::HTTP_SEE_OTHER);
    }
}
