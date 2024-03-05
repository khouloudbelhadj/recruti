<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RendezvousRepository;

class TemplateController extends AbstractController
{
    #[Route('/front', name: 'app_front')]
    public function indexFront(): Response
    {
        return $this->render('baseFront.html.twig', [
            'controller_name' => 'TemplateController',
        ]);
    }

    #[Route('/back', name: 'app_back')]
    public function indexBack(): Response
    {
        return $this->render('baseBack.html.twig', [
            'controller_name' => 'TemplateController',
        ]);
    }
    
    #[Route('/frontt', name: 'app_frontt')]
    public function index(): Response
    {
        return $this->render('front.html.twig', [
            'controller_name' => 'TemplateController',
        ]);
    }
    
    #[Route('/plan', name: 'app_plan')]
    public function indexx(RendezvousRepository $rendezvousRepository): Response
    {
        $rendezvouses = $rendezvousRepository->findAll();
        return $this->render('planification/home.html.twig', [
            'controller_name' => 'TemplateController',
            'rendezvouses' => $rendezvouses, // Passer les rendezvouses au template Twig
        ]);
    }
    
    #[Route('/new-route', name: 'app_new_route')]
    public function newRoute(): Response
    {
        return $this->render('rendezvous/create.html.twig', [
            'controller_name' => 'TemplateController',
        ]);
    }
}
