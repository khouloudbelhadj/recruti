<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TemplateController extends AbstractController
{
    #[Route('/front', name: 'app_front')]
    public function indexFront(): Response
    {
        return $this->render('baseFront.html.twig', [
            'controller_name' => 'TemplateController',
        ]);
    }
    #[Route('/front/profile', name: 'app_front_profile')]
    public function profile(): Response
    {
        return $this->render('profile.html.twig', [
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
}
