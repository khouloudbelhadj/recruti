<?php

namespace App\Controller;
use App\Entity\Rendezvous;
use App\Form\RendezvousType;
use App\Repository\RendezvousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/rendezvous')]
class RendezvousController extends AbstractController
{
    #[Route('/', name: 'app_rendezvous_index', methods: ['GET'])]
public function index(RendezvousRepository $rendezvousRepository, Request $request, PaginatorInterface $paginator): Response
{  
    $lieu = $request->query->get('lieu');
    $emailRepresen = $request->query->get('emailRepresen');

    $repository = $this->getDoctrine()->getRepository(Rendezvous::class);
    $queryBuilder = $repository->createQueryBuilder('r');

    // Appliquer les filtres s'ils sont définis
    if ($lieu) {
        $queryBuilder->andWhere('r.lieu = :lieu')
                     ->setParameter('lieu', $lieu);
    }

    if ($emailRepresen) {
        $queryBuilder->andWhere('r.emailRepresen = :emailRepresen')
                     ->setParameter('emailRepresen', $emailRepresen);
    }

    // Paginer les résultats
    $rendezvouses = $paginator->paginate(
        $queryBuilder->getQuery(), // Query à paginer
        $request->query->getInt('page', 1), // Numéro de la page à afficher, par défaut 1
        6 // Nombre d'éléments par page
    );

    // Passer les rendez-vous paginés à votre modèle Twig
    return $this->render('rendezvous/index.html.twig', [
        'rendezvouses' => $rendezvouses,
    ]);
}

    #[Route('/rendezvous/{id}', name: 'app_rendezvous_show_detail', methods: ['GET'])]
    public function showDetail(int $id, RendezvousRepository $rendezvousRepository): Response
    {
        $rendezvous = $rendezvousRepository->find($id);

        if (!$rendezvous) {
            throw $this->createNotFoundException('Rendezvous not found');
        }

        return $this->render('rendezvous/detail.html.twig', [
            'rendezvous' => $rendezvous,
        ]);
    }

   
   
    #[Route('/create', name: 'app_rendezvous_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $rendezvous = new Rendezvous();
    $rendezvous->setDateRendez(new \DateTime());

    $form = $this->createForm(RendezvousType::class, $rendezvous);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($rendezvous);
        $entityManager->flush();

        // Redirect to the details page of the newly created rendezvous
        return $this->redirectToRoute('app_rendezvous_show_detail', ['id' => $rendezvous->getId()]);
    }

    return $this->render('rendezvous/new.html.twig', [
        'form' => $form->createView(),
    ]);
}

    #[Route('/{id}', name: 'app_rendezvous_show', methods: ['GET'])]
    #[ParamConverter('rendezvou', class: Rendezvous::class)]
    public function show(Rendezvous $rendezvous): Response
    {
        return $this->render('rendezvous/show.html.twig', [
            'rendezvous' => $rendezvous,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_rendezvous_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Rendezvous $rendezvou, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RendezvousType::class, $rendezvou);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_rendezvous_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rendezvous/edit.html.twig', [
            'rendezvou' => $rendezvou,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rendezvous_delete', methods: ['POST'])]
    public function delete(Request $request, Rendezvous $rendezvou, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $rendezvou->getId(), $request->request->get('_token'))) {
            $entityManager->remove($rendezvou);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_rendezvous_index', [], Response::HTTP_SEE_OTHER);
    }
    
}
