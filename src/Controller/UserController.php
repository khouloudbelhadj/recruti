<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Options;

use Dompdf\Dompdf;


#[Route('/user')]
class UserController extends AbstractController
{
    
    
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
        }

    
        #[Route('/search', name: 'app_user_search', methods: ['GET'])]

        public function search(Request $request, UserRepository $userRepository): Response
{
    $query = $request->query->get('query');
    $searchAttribute = $request->query->get('search_attribute');

    if (!empty($query) && !empty($searchAttribute)) {
        $users = $userRepository->findBySearchQuery($query, $searchAttribute);
    } else {
        $users = $userRepository->findAll();
    }

    return $this->render('user/index.html.twig', [
        'users' => $users,
    ]);
}

// UserController.php

#[Route('/sort', name: 'app_user_sort', methods: ['GET'])]
public function sort(UserRepository $userRepository, Request $request): Response
{
    
    $sortAttribute = $request->query->get('sort_attribute');
    $order = $request->query->get('order', 'ASC');
    
    // Fetch the sorted list of users
    $users = $userRepository->findAllSorted($sortAttribute, $order);

    return $this->render('user/index.html.twig', [
        'users' => $users,
    ]);
}

        

        #[Route('/pdf', name: 'app_user_pdf', methods: ['GET', 'POST'])]
public function generatePdf(UserRepository $userRepository): Response
{
    // Get all users from the repository
    $users = $userRepository->findAll();
    
    // Render the Twig template with the list of users
    $html = $this->renderView('user/pdf.html.twig', ['users' => $users]);
    
    // Set up Dompdf options and render the PDF
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);
    
    $dompdf = new Dompdf();
    $dompdf->setOptions($options);
    $dompdf->loadHtml($html);
    $dompdf->render();
    
    // Return the PDF as a response
    return new Response(
        $dompdf->output(),
        Response::HTTP_OK,
        ['Content-Type' => 'application/pdf']
    );
}



    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    #[ParamConverter('user', class: User::class)]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
